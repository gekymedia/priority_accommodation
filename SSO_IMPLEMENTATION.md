# SSO Implementation for Accommodation Portal

## Overview
This document describes the Single Sign-On (SSO) implementation that allows CUG Admissions Portal users to automatically log in to the Accommodation Portal.

## Implementation Details

### 1. Database Changes
- **Migration**: `2026_01_13_102716_add_app_id_to_users_table.php`
- **Added Field**: `app_id` (string, nullable, unique) to `users` table
- **Purpose**: Links accommodation portal users to their CUG application ID

### 2. User Model Updates
- Added `app_id` to `$fillable` array
- Users can now be identified by their CUG application ID

### 3. SSO Controller
**File**: `app/Http/Controllers/SsoController.php`
**Route**: `/sso/login` (public route, no authentication required)

#### How It Works:
1. Receives SSO token from CUG portal via query parameters
2. Decodes and verifies the token:
   - Checks token format
   - Verifies expiration (5 minutes)
   - Validates HMAC signature using Laravel's `APP_KEY`
3. Extracts user data (app_id, email, name, phone)
4. Finds or creates user:
   - First tries to find by `app_id`
   - Falls back to `email` if `app_id` not found
   - Creates new user if neither exists
5. Updates user with CUG verification:
   - Sets `is_cug_verified = true`
   - Sets `cug_verified_at = now()`
   - Sets `profile_complete = true`
6. Logs user in automatically
7. Redirects to appropriate dashboard (admin or user)

### 4. Route Configuration
**Route**: `GET /sso/login`
**Controller**: `SsoController@login`
**Middleware**: None (public route)

## Configuration

### Required Environment Variables
Both systems (CUG and Accommodation) must share the same `APP_KEY`:

```env
# Both .env files should have the same APP_KEY
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Important**: For production, consider using a dedicated SSO secret key instead of the app key for better security isolation.

### Optional Configuration
You can configure the CUG portal URL in the accommodation portal's `.env`:

```env
CUG_PORTAL_URL=https://cug.prioritysolutionsagency.com
```

## Security Features

1. **Token Expiration**: Tokens expire after 5 minutes
2. **HMAC Signature**: Prevents token tampering
3. **Session Regeneration**: Prevents session fixation attacks
4. **Logging**: All SSO attempts are logged for security auditing

## User Flow

1. User logs into CUG Admissions Portal
2. User clicks "Accommodation" button in sidebar
3. CUG generates SSO token and redirects to:
   ```
   https://accomodation.prioritysolutionsagency.com/sso/login?token=xxx&app_id=xxx&email=xxx&name=xxx
   ```
4. Accommodation portal verifies token
5. User is automatically logged in
6. User is redirected to their dashboard

## Error Handling

The SSO controller handles various error scenarios:
- Missing token → Redirects to login with error message
- Invalid token format → Redirects to login with error message
- Expired token → Redirects to login with error message
- Invalid signature → Redirects to login with error message
- Missing user data → Redirects to login with error message
- System errors → Logs error and redirects to login

## Testing

### To Test SSO:
1. Ensure both systems have the same `APP_KEY`
2. Run the migration: `php artisan migrate`
3. Log into CUG portal as an applicant
4. Click "Accommodation" in the sidebar
5. Verify automatic login to accommodation portal

### Manual Testing:
You can test the SSO endpoint directly by generating a token (see CUG's `AccommodationSsoController` for token generation logic).

## Troubleshooting

### "Invalid SSO token"
- Check that both systems have the same `APP_KEY`
- Verify token hasn't expired (5 minutes)
- Check logs for detailed error messages

### "SSO token has expired"
- User took too long to click the link
- Refresh and try again from CUG portal

### "Invalid SSO signature"
- `APP_KEY` mismatch between systems
- Token may have been tampered with
- Check network security

### User not found/created
- Check database connection
- Verify migration has been run
- Check logs for creation errors

## Logging

All SSO attempts are logged with:
- Success: User ID, app_id, email
- Failures: Error type, token details (truncated), user data

Check logs at: `storage/logs/laravel.log`

## Future Enhancements

1. **Dedicated SSO Secret**: Use separate key from `APP_KEY`
2. **Token Refresh**: Implement refresh tokens for longer sessions
3. **Rate Limiting**: Add rate limiting to prevent abuse
4. **Audit Trail**: Enhanced audit logging for compliance
5. **Multi-tenant Support**: Support for multiple CUG instances
