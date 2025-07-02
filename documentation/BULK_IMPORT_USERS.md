# Bulk Import Users Feature

## Overview
The Bulk Import Users feature allows master administrators to efficiently import multiple users from CSV files. This feature is designed to streamline user onboarding and management by enabling batch creation of students, club managers, and other master admins.

## Access & Permissions
- **Access Level:** Master Admin only
- **Location:** Available from the User Management page (`/users`) via the "Import Users" button
- **URL:** `/admin/users/import`

## Supported File Formats
- **Primary Format:** CSV (Comma Separated Values)
- **File Extensions:** `.csv`, `.txt`
- **Maximum File Size:** 10MB
- **Encoding:** UTF-8 recommended

## CSV File Structure

### Required Columns
1. **name** - Full name of the user
2. **email** - Valid email address (must be unique)
3. **role** - User role (student, club_manager, master_admin)

### Optional Columns
1. **password** - User password (defaults to 'password123' if not provided)

### Example CSV Format
```csv
name,email,role,password
John Doe,john@example.com,student,mypassword123
Jane Smith,jane@example.com,club_manager,securepass456
Admin User,admin@example.com,master_admin,adminpass789
```

## Import Process

### Step 1: Access Import Page
1. Navigate to User Management (`/users`)
2. Click the "Import Users" button in the Bulk Import section
3. You'll be redirected to the import interface

### Step 2: Download Template (Optional)
1. Click "Download Template CSV" to get a properly formatted example file
2. Use this template as a starting point for your import data

### Step 3: Prepare Your CSV File
1. Ensure all required columns are present
2. Validate email addresses are unique and properly formatted
3. Use only valid role values: `student`, `club_manager`, `master_admin`
4. Check that names and passwords meet system requirements

### Step 4: Upload and Import
1. Select your CSV file using the file picker
2. Click "Import Users" to start the process
3. Review the import results and any error messages

## Validation Rules

### Email Validation
- Must be a valid email format
- Must be unique across all existing users
- Duplicate emails in the import file will be skipped

### Role Validation
- Only accepts: `student`, `club_manager`, `master_admin`
- Case-insensitive matching
- Invalid roles will cause the row to be skipped

### Name Validation
- Required field, cannot be empty
- Maximum 255 characters
- Accepts any valid UTF-8 characters

### Password Validation
- Minimum 6 characters if provided
- If not provided, defaults to 'password123'
- Passwords are automatically hashed for security

## Import Results

### Success Indicators
- **Imported Count:** Number of users successfully created
- **Skipped Count:** Number of rows skipped due to errors
- **Error Details:** Specific issues logged for review

### Common Skip Reasons
1. **Duplicate Email:** User with same email already exists
2. **Invalid Role:** Role not recognized by the system
3. **Missing Data:** Required fields (name, email, role) are empty
4. **Validation Errors:** Data doesn't meet system requirements

## Error Handling

### User-Friendly Messages
- Success messages show import statistics
- Error messages provide clear guidance
- Warnings indicate when some users were skipped

### System Logging
- All import activities are logged for audit purposes
- Error details are recorded in system logs
- Admin actions are tracked with timestamps and user information

### Log Locations
- **Application Logs:** `storage/logs/laravel.log`
- **Activity Logs:** Accessible via System Logs dashboard
- **Import Errors:** Detailed in log files with admin email reference

## Best Practices

### File Preparation
1. **Test Small Batches:** Start with 10-20 users to validate format
2. **Check for Duplicates:** Ensure no duplicate emails in your CSV
3. **Validate Roles:** Double-check all role assignments
4. **Use Template:** Start with the provided template for consistency

### Import Strategy
1. **Backup First:** Always backup existing user data before large imports
2. **Import in Batches:** For large datasets, split into smaller files
3. **Review Results:** Check import statistics and error logs
4. **Verify Users:** Confirm imported users appear correctly in the system

### Security Considerations
1. **Secure Files:** Keep import files confidential and delete after use
2. **Default Passwords:** Change default passwords on first login
3. **Email Verification:** Imported users maintain email verification status
4. **Access Control:** Only master admins can perform imports

## Troubleshooting

### Common Issues

#### "File too large" Error
- **Cause:** File exceeds 10MB limit
- **Solution:** Split large files into smaller batches

#### "Invalid file format" Error
- **Cause:** File is not CSV format
- **Solution:** Save file as CSV from Excel or other applications

#### "Required column missing" Error
- **Cause:** CSV missing name, email, or role columns
- **Solution:** Add missing columns using the template as reference

#### High Skip Rate
- **Cause:** Many duplicate emails or invalid data
- **Solution:** Clean data before import, remove duplicates

### Getting Help
1. **Check System Logs:** Review detailed error messages in logs
2. **Download Template:** Use the provided template for proper formatting
3. **Test Small Batches:** Validate format with a few test users first
4. **Contact Administrator:** For persistent issues, contact system administrator

## Integration with Existing Features

### User Management
- Imported users appear immediately in the user management interface
- All standard user management features apply to imported users
- Role assignments are automatically applied

### Email Verification
- Imported users are marked as email verified by default
- Users can still receive password reset emails if needed
- Email verification status can be modified post-import

### Club Management
- Club managers imported can be assigned to clubs immediately
- Students can be added to clubs through standard processes
- No additional setup required for imported users

## Security & Compliance

### Data Protection
- Import process follows standard Laravel security practices
- Passwords are properly hashed using bcrypt
- File uploads are validated and sanitized

### Audit Trail
- All import activities are logged with timestamps
- Admin performing import is recorded
- Success/failure statistics are maintained

### Access Control
- Feature restricted to master admin role only
- All standard authentication and authorization apply
- Session management follows existing security policies

## Performance Considerations

### File Size Limits
- 10MB maximum file size to prevent server overload
- Large imports may take several minutes to process
- Browser timeout considerations for very large files

### Batch Processing
- Import processes files sequentially
- Memory usage optimized for large datasets
- Recommended batch size: 1000-5000 users per file

### System Impact
- Imports may temporarily increase database load
- Consider off-peak hours for large imports
- Monitor system resources during large batch imports
