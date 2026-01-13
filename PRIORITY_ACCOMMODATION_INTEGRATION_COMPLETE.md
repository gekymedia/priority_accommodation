# Priority Accommodation - Priority Bank Integration Complete ✅

## What Was Implemented

1. **Priority Bank API Client** (`app/Services/PriorityBankApiClient.php`)
   - Handles communication with Priority Bank API
   - Automatic retry logic with exponential backoff
   - Idempotency support

2. **Integration Service** (`app/Services/PriorityBankIntegrationService.php`)
   - Pushes completed payments to Priority Bank
   - Maps payment types correctly:
     - **Rent & Security Deposits** → Income in Priority Bank
     - **Maintenance** → Expense in Priority Bank
   - Handles payment method mapping to channels

3. **Controller Updates**
   - `PaymentController`: Automatically pushes payments to Priority Bank when:
     - Payment is created with status 'completed'
     - Payment status is updated to 'completed'
     - Payment is marked as completed via `markCompleted` method

4. **Webhook Endpoints** (`app/Http/Controllers/PriorityBankWebhookController.php`)
   - Receives expense data from Priority Bank
   - Creates local maintenance payment records when CEO creates entries in Priority Bank
   - Note: Income webhooks are logged only (Priority Accommodation uses payment records, not direct income)

5. **Configuration**
   - Added Priority Bank settings to `config/services.php`
   - Ready for environment variable configuration

## Environment Configuration Required

Add to Priority Accommodation's `.env` file:

```env
# Priority Bank Central Finance API
PRIORITY_BANK_API_URL=https://prioritybank.gekymedia.com
PRIORITY_BANK_API_TOKEN=your_token_here
PRIORITY_BANK_API_TIMEOUT=10
PRIORITY_BANK_API_MAX_RETRIES=3
```

## How It Works

### 1. Priority Accommodation → Priority Bank (Automatic Push)

**Rent & Security Deposits (Income):**
- When payment is created/updated and status is 'completed'
- Payment type: `rent` or `security`
- Automatically pushed to Priority Bank as income
- Category: "Rent" or "Security Deposits"

**Maintenance Payments (Expenses):**
- When payment is created/updated and status is 'completed'
- Payment type: `maintenance`
- Automatically pushed to Priority Bank as expense
- Category: "Maintenance"

If Priority Bank API is unavailable, error is logged but Priority Accommodation operations still succeed.

### 2. Priority Bank → Priority Accommodation (Webhook)

When CEO creates expense in Priority Bank and selects "Priority Accommodation":
- Priority Bank saves the record
- Priority Bank sends webhook to: `https://[domain]/api/webhook/finance/expense`
- Priority Accommodation receives webhook and creates local maintenance payment record
- Transaction is now in both systems

**Note:** Income webhooks are logged only because Priority Accommodation uses structured payment records rather than direct income entries.

## Payment Type Mapping

| Payment Type | Priority Bank Transaction Type | Category |
|-------------|-------------------------------|----------|
| `rent` | Income | "Rent" |
| `security` | Income | "Security Deposits" |
| `maintenance` | Expense | "Maintenance" |
| `other` | Income (default) | Uses payment description |

## Payment Method to Channel Mapping

| Payment Method | Priority Bank Channel |
|---------------|---------------------|
| `cash` | `cash` |
| `bank_transfer` | `bank` |
| `card` | `bank` |
| `upi` | `momo` |

## Testing

1. **Test Rent Payment Push:**
   - Create a payment with type 'rent' and status 'completed'
   - Check Priority Bank to see if it appears as income
   - Check logs: `storage/logs/laravel.log`

2. **Test Security Deposit Push:**
   - Create a payment with type 'security' and status 'completed'
   - Check Priority Bank to see if it appears as income

3. **Test Maintenance Payment Push:**
   - Create a payment with type 'maintenance' and status 'completed'
   - Check Priority Bank to see if it appears as expense

4. **Test Payment Status Update:**
   - Create a payment with status 'pending'
   - Update status to 'completed'
   - Check Priority Bank to see if it appears

5. **Test Webhook:**
   - Create expense in Priority Bank
   - Select "Priority Accommodation" system
   - Check Priority Accommodation Payments to see if maintenance payment record appears
   - Check Priority Bank logs for webhook delivery status

## Next Steps

1. Get API token from Priority Bank administrator
2. Add token to `.env` file
3. Update Priority Bank systems registry with Priority Accommodation callback URL:
   ```
   https://[your-domain]/api/webhook/finance/income
   https://[your-domain]/api/webhook/finance/expense
   ```
4. Test the integration
5. Monitor logs for any issues

## Notes

- Integration is non-blocking: If Priority Bank API fails, Priority Accommodation operations still succeed
- All API calls are logged for debugging
- Webhook endpoints are public (no authentication required by default - consider adding if needed)
- Only completed payments are pushed (status must be 'completed')
- Payment metadata includes receipt number, student info, booking info, and transaction details
- Webhook expense creation requires an active booking - if no active bookings exist, webhook will fail gracefully with an error message

## Important: Webhook Expense Creation

When Priority Bank sends an expense webhook:
- The system attempts to find an active booking to associate the expense with
- If no active booking is found, the webhook will return an error
- Consider enhancing this in the future to handle expenses that don't require a booking association

