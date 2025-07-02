# Bulk User Import Feature

## Overview
The Bulk User Import feature allows master administrators to import multiple users from CSV files efficiently. This feature is accessible from the User Management page and supports importing students, club managers, and master admins.

## Access Requirements
- **Role Required**: Master Admin only
- **Permission**: Full user management access

## How to Use

### 1. Navigate to Import Page
- Go to User Management: `http://127.0.0.1:8000/users`
- Click the "Import Users" button in the Bulk Import section
- This will take you to: `http://127.0.0.1:8000/admin/users/import`

### 2. Download Template (Optional)
- Click "Download Template CSV" to get a sample file with the correct format
- The template includes example users for each role type

### 3. Prepare Your CSV File
Your CSV file must include these columns:
- **name** (required): Full name of the user
- **email** (required): Email address (must be unique)
- **role** (required): One of: `student`, `club_manager`, `master_admin`
- **password** (optional): If not provided, defaults to 'password123'

### 4. Upload and Import
- Select your CSV file (max 10MB)
- Click "Import Users"
- Review the import results

## CSV Format Example
```csv
name,email,role,password
John Doe,john@example.com,student,password123
Jane Smith,jane@example.com,club_manager,securepass456
Admin User,admin@example.com,master_admin,adminpass789
```

## Import Results
After processing, you'll receive a summary including:
- Number of users successfully imported
- Number of users skipped (duplicates or errors)
- Details about any errors encountered

## Error Handling
The system will skip users that:
- Have duplicate email addresses
- Have invalid role values
- Are missing required fields
- Cause database errors

All errors are logged for administrative review.

## File Requirements
- **Format**: CSV only
- **Size**: Maximum 10MB
- **Encoding**: UTF-8 recommended
- **Headers**: First row must contain column names

## Security Features
- All imported users are automatically verified
- Passwords are securely hashed
- Import activity is logged
- Only master admins can access this feature

## Routes
- Import form: `GET /admin/users/import`
- Process import: `POST /admin/users/import`
- Download template: `GET /admin/users/import/template`

## Implementation Details
- **Controller**: `App\Http\Controllers\Admin\UserController`
- **Methods**: `showImportForm()`, `importUsers()`, `downloadTemplate()`
- **View**: `resources/views/admin/import-users.blade.php`
- **Validation**: File type, size, and CSV structure validation
- **Logging**: Import activities and errors are logged to Laravel logs

## Troubleshooting
1. **Import fails**: Check CSV format and file encoding
2. **Users not created**: Verify role names are exactly: student, club_manager, or master_admin
3. **Duplicate errors**: Check for existing users with same email addresses
4. **File upload errors**: Ensure file is under 10MB and is a valid CSV

## Future Enhancements
- Support for XLSX/XLS files
- Bulk role assignment updates
- Import preview functionality
- Advanced validation rules
- Custom password policies
