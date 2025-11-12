# Postman Image Upload Testing Guide

## Prerequisites
1. Make sure you have a valid admin access token
2. The server should be running

## Testing Image Upload

### 1. Create Service with Image

**Endpoint:** `POST /api/admin/services`

**Headers:**
```
Authorization: Bearer {your_access_token}
Accept: application/json
```

**Body Type:** `form-data`

**Body Fields:**
- `name` (Text): Service name, e.g., "غسيل الملابس"
- `description` (Text, optional): Service description
- `price` (Text): Service price, e.g., "50.00"
- `is_active` (Text): "1" for active, "0" for inactive
- `image` (File): Select an image file (jpg, png, gif, max 2MB)

**Expected Response (201):**
```json
{
    "status": "success",
    "message": "تم إنشاء الخدمة بنجاح",
    "data": {
        "id": 1,
        "name": "غسيل الملابس",
        "description": "...",
        "price": "50.00",
        "is_active": true,
        "image_url": "http://your-app-url/storage/media/1/filename.jpg",
        "media": [...]
    }
}
```

### 2. Update Service with New Image

**Endpoint:** `PUT /api/admin/services/{id}` or `PATCH /api/admin/services/{id}`

**Headers:**
```
Authorization: Bearer {your_access_token}
Accept: application/json
```

**Body Type:** `form-data`

**Body Fields:**
- `name` (Text, optional): Updated service name
- `description` (Text, optional): Updated description
- `price` (Text, optional): Updated price
- `is_active` (Text, optional): "1" or "0"
- `image` (File): Select a new image file to replace the old one

**Note:** If you include an `image` field, the old image will be automatically deleted and replaced with the new one.

**Expected Response (200):**
```json
{
    "status": "success",
    "message": "تم تحديث الخدمة بنجاح",
    "data": {
        "id": 1,
        "name": "Updated Name",
        "image_url": "http://your-app-url/storage/media/1/new-filename.jpg",
        "media": [...]
    }
}
```

### 3. Update Service Without Image

**Endpoint:** `PUT /api/admin/services/{id}` or `PATCH /api/admin/services/{id}`

**Headers:**
```
Authorization: Bearer {your_access_token}
Accept: application/json
```

**Body Type:** `form-data`

**Body Fields:**
- `name` (Text, optional): Updated service name
- `price` (Text, optional): Updated price
- (Do NOT include `image` field)

**Expected Response (200):** The service will be updated but the image will remain unchanged.

### 4. Get Service with Image URL

**Endpoint:** `GET /api/services/{id}` or `GET /api/admin/services/{id}`

**Headers:**
```
Authorization: Bearer {your_access_token}
Accept: application/json
```

**Expected Response (200):**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "name": "Service Name",
        "image_url": "http://your-app-url/storage/media/1/filename.jpg",
        "media": [...]
    }
}
```

## Important Notes

1. **Image Field in Postman:**
   - In Postman, select `form-data` as body type
   - Add a field named `image`
   - Change the field type from "Text" to "File" using the dropdown
   - Click "Select Files" to choose your image

2. **Image Requirements:**
   - Allowed formats: jpeg, png, jpg, gif
   - Maximum size: 2MB
   - The image field is optional

3. **Storage:**
   - Images are stored using Spatie Media Library
   - Images are accessible via the `image_url` attribute
   - Old images are automatically deleted when updating

4. **Response Format:**
   - The `image_url` attribute is automatically included in JSON responses
   - The `media` relationship is also loaded for additional metadata

