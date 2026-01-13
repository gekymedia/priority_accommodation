# Priority Accommodation - Complaint System & API Integration Update

## Summary

Two major updates have been implemented:

1. **Priority Bank API Integration Logic Update** - Fixed to correctly handle the broker business model
2. **Student Complaint System** - New feature allowing students to log and track complaints

---

## 1. Priority Bank API Integration Update

### Business Model Understanding

Priority Accommodation operates as a **broker/agent**, not a hostel owner:
- Finds available hostels
- Adds profit margin (commission) to room costs
- Advertises on Priority Admissions system
- Receives full payment from students
- Pays hostel owners their portion (base_price)

### Changes Made

**File:** `app/Services/PriorityBankIntegrationService.php`

**Updates:**
- Added broker model metadata to all payment transactions
- Tracks `base_price` (amount to hostel owner)
- Tracks `commission_amount` (Priority Accommodation profit)
- Tracks `total_amount` (full payment received from student)
- Calculates and includes `profit_margin` percentage
- Includes hostel information in metadata

**Financial Flow:**
- **Income:** Full payment received from student (`total_amount`) - This is Priority Accommodation's revenue
- **Expense:** When paying out to hostel owner (`base_price`) - This will be tracked separately when payout feature is implemented

**Example Metadata:**
```json
{
  "broker_model": true,
  "base_price": 500.00,
  "commission_amount": 50.00,
  "total_amount": 550.00,
  "hostel_id": 1,
  "hostel_name": "ABC Hostel",
  "profit_margin": 9.09
}
```

---

## 2. Student Complaint System

### Overview

Students can now log complaints about their accommodation, which are tracked and managed by administrators.

### Features

- **Student Features:**
  - Submit complaints with subject, description, category, and priority
  - View their complaint history
  - Track complaint status
  - Receive admin responses

- **Admin Features:**
  - View all complaints with filtering
  - Assign complaints to staff
  - Update status (pending, in_progress, resolved, closed, rejected)
  - Respond to complaints
  - View complaint statistics

### Database Structure

**Table:** `complaints`

**Fields:**
- `student_id` - Student who submitted the complaint
- `booking_id` - Related booking (optional)
- `room_id` - Related room (optional)
- `hostel_id` - Related hostel (optional)
- `subject` - Complaint subject
- `description` - Detailed description
- `category` - maintenance, noise, security, cleanliness, utilities, roommate, other
- `priority` - low, medium, high, urgent
- `status` - pending, in_progress, resolved, closed, rejected
- `admin_response` - Admin's response/resolution
- `assigned_to` - Admin user assigned to handle
- `resolved_by` - Admin who resolved it
- `resolved_at` - Resolution timestamp

### Files Created

1. **Model:** `app/Models/Complaint.php`
   - Full model with relationships, scopes, and business logic methods
   - Status and priority management
   - Timeline tracking

2. **Migration:** `database/migrations/2025_12_25_053230_create_complaints_table.php`
   - Complete table structure with indexes

3. **Controller:** `app/Http/Controllers/ComplaintController.php`
   - `index()` - Admin view of all complaints
   - `create()` - Student form to submit complaint
   - `store()` - Save new complaint
   - `show()` - View single complaint
   - `edit()` - Admin form to manage complaint
   - `update()` - Update complaint status/response
   - `myComplaints()` - Student's complaint history

4. **Views:**
   - `resources/views/complaints/index.blade.php` - Admin dashboard
   - `resources/views/complaints/create.blade.php` - Submit complaint form
   - `resources/views/complaints/show.blade.php` - View complaint details
   - `resources/views/complaints/edit.blade.php` - Admin management form
   - `resources/views/complaints/my-complaints.blade.php` - Student's complaints

5. **Routes:** Added to `routes/web.php`
   - `GET /complaints` - Admin index
   - `GET /complaints/create` - Submit complaint
   - `POST /complaints` - Store complaint
   - `GET /complaints/{complaint}` - View complaint
   - `GET /complaints/{complaint}/edit` - Edit complaint (admin)
   - `PUT /complaints/{complaint}` - Update complaint
   - `GET /complaints/my-complaints` - Student's complaints

### Complaint Categories

- **Maintenance** - Room/hostel maintenance issues
- **Noise** - Noise complaints
- **Security** - Security concerns
- **Cleanliness** - Cleanliness issues
- **Utilities** - Water, electricity, internet issues
- **Roommate** - Roommate-related issues
- **Other** - Other complaints

### Complaint Priorities

- **Low** - Non-urgent issues
- **Medium** - Standard priority (default)
- **High** - Important issues
- **Urgent** - Critical issues requiring immediate attention

### Complaint Statuses

- **Pending** - Newly submitted, awaiting review
- **In Progress** - Being handled by assigned admin
- **Resolved** - Issue resolved with admin response
- **Closed** - Complaint closed
- **Rejected** - Complaint rejected (with reason)

### Usage

**For Students:**
1. Navigate to "My Complaints" or "Submit Complaint"
2. Fill out complaint form with details
3. Select category and priority
4. Submit and track status
5. View admin responses

**For Admins:**
1. Navigate to "Complaints Management"
2. View all complaints with filters
3. Assign complaints to staff
4. Update status and add responses
5. Resolve or close complaints

### Access Control

- Students can only view their own complaints
- Admins can view and manage all complaints
- Uses Spatie Permission roles for authorization

---

## Next Steps

1. **Run Migration:**
   ```bash
   php artisan migrate
   ```

2. **Add Navigation Links:**
   - Add "Complaints" link to student navigation
   - Add "Complaints Management" link to admin navigation

3. **Optional Enhancements:**
   - Email notifications when complaints are submitted/updated
   - SMS notifications for urgent complaints
   - Complaint escalation rules
   - Hostel payout tracking (expense when paying hostel owners)

---

## Testing Checklist

- [ ] Run migration successfully
- [ ] Student can submit complaint
- [ ] Student can view their complaints
- [ ] Admin can view all complaints
- [ ] Admin can assign complaints
- [ ] Admin can update status
- [ ] Admin can add responses
- [ ] Priority Bank integration includes broker metadata
- [ ] Payment transactions show profit breakdown

---

**Implementation Date:** December 25, 2025
**Status:** ✅ Complete and ready for testing

