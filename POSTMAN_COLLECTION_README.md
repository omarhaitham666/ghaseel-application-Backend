# Ghaseel API Postman Collection

This Postman collection contains all API endpoints for the Ghaseel Application Backend.

## 📥 Import the Collection

1. Open Postman
2. Click **Import** button (top left)
3. Select the file `Ghaseel_API_Collection.postman_collection.json`
4. Click **Import**

## 🔧 Setup Environment Variables

The collection uses environment variables for easier testing:

### Required Variables:
- `base_url`: Your API base URL (default: `http://localhost:8000`)
- `access_token`: Authentication token (automatically set after login)

### To Set Up:
1. In Postman, click on **Environments** (left sidebar)
2. Create a new environment or use **Globals**
3. Add these variables:
   - `base_url`: `http://localhost:8000` (or your server URL)
   - `access_token`: (leave empty, will be set automatically after login)

## 🚀 Usage Instructions

### 1. Authentication Flow

1. **Register** - Create a new user account
2. **Verify** - Verify phone number with verification code
3. **Login** - Login and get access token (token is automatically saved)
4. Use the token for authenticated requests

### 2. Testing Endpoints

#### Public Endpoints (No Auth Required):
- Get Active Services
- Register, Login, Verify, etc.

#### Authenticated Endpoints (Require Token):
- User Locations
- Orders
- Cart
- Service Details

#### Admin Endpoints (Require Admin Token):
- Admin Services Management
- Admin Orders Management
- Dashboard Statistics

## 📋 Endpoint Groups

### Authentication
- Register
- Verify
- Login (auto-saves token)
- Logout
- Change Password
- Forgot Password
- Reset Password
- Resend Code

### Services
- Get Active Services (Public)
- Get Service Details (Auth)

### User Locations
- Get User Locations
- Create Location
- Update Location
- Delete Location

### Orders
- Get User Orders
- Create Order
- Get Order Details

### Cart
- Get Cart Items
- Add to Cart
- Update Cart Item
- Clear Cart
- Delete Cart Item
- My Cart

### Admin - Services
- Get All Services
- Create Service (with image upload)
- Update Service (with image upload)
- Delete Service

### Admin - Orders
- Get All Orders
- Get Order Details
- Accept Order
- Reject Order
- Update Order Status
- Delete Order

### Admin - Dashboard
- Get Dashboard Statistics

## 📝 Important Notes

### Image Uploads
For service creation/update with images:
1. Use **form-data** body type
2. Add image file in the `image` field
3. Add other fields as text

### Request Body Examples

#### Create Order:
```json
{
    "service_ids": [1, 2],
    "location_id": 1,
    "delivery_type": "normal",
    "pickup_date": "2024-12-25",
    "pickup_time": "10:00",
    "delivery_date": "2024-12-26",
    "delivery_time": "14:00",
    "notes": "Please handle with care"
}
```

#### Create Service (form-data):
- `name[ar]`: "خدمة التنظيف"
- `name[en]`: "Cleaning Service"
- `description[ar]`: `["تنظيف شامل", "تنظيف عميق"]`
- `description[en]`: `["Full cleaning", "Deep cleaning"]`
- `is_active`: "true"
- `image`: (file)

### Status Values

#### Order Status:
- `pending`
- `processing`
- `completed`
- `delivered`

#### Delivery Type:
- `normal`
- `express`

## 🔐 Authentication

After successful login, the access token is automatically saved to the `access_token` variable. All authenticated endpoints will use this token via the `Authorization: Bearer {{access_token}}` header.

## 🐛 Troubleshooting

1. **401 Unauthorized**: Make sure you're logged in and the token is valid
2. **403 Forbidden**: Check if you have admin role for admin endpoints
3. **404 Not Found**: Verify the base_url is correct
4. **422 Validation Error**: Check request body format and required fields

## 📞 Support

For issues or questions, refer to the API documentation or contact the development team.

