# Database Seeders Documentation

This document describes all the database seeders available in the Ghaseel Application.

## 📋 Available Seeders

### 1. UserSeeder
Creates users for testing:
- **1 Admin user**: `admin@ghaseel.com` / `password123`
- **5 Regular users**: Various test users with password `password123`

**Users created:**
- Admin: admin@ghaseel.com
- Ahmed Ali: ahmed@example.com
- Fatima Hassan: fatima@example.com
- Mohammed Saleh: mohammed@example.com
- Sara Abdullah: sara@example.com
- Khalid Ibrahim: khalid@example.com

### 2. ServiceSeeder
Creates 8 services with Arabic and English names and descriptions:
- Cleaning Service (خدمة التنظيف)
- Laundry and Ironing Service (خدمة الغسيل والكي)
- Maintenance Service (خدمة الصيانة)
- Cooking Service (خدمة الطبخ)
- Gardening Service (خدمة البستنة)
- Childcare Service (خدمة رعاية الأطفال)
- Elderly Care Service (خدمة رعاية المسنين)
- Delivery Service (خدمة التوصيل)

### 3. UserLocationSeeder
Creates locations for all regular users:
- Each user gets at least 1 location
- First 2 users get 2 locations (Home and Work)
- Locations include addresses in Riyadh, Jeddah, and Dammam

### 4. OrderSeeder
Creates orders for all users:
- 2-4 orders per user
- Random admin statuses (pending, accepted, rejected)
- Random order statuses for accepted orders (processing, completed, delivered)
- Random delivery types (normal, express)
- Each order is linked to 1-3 random services
- Orders have realistic pickup and delivery dates

### 5. CartSeeder
Creates cart items:
- Creates carts for all accepted orders
- Creates some carts for rejected orders
- Links carts to their respective orders

## 🚀 Usage

### Run All Seeders
```bash
php artisan db:seed
```

### Run Specific Seeder
```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=ServiceSeeder
php artisan db:seed --class=UserLocationSeeder
php artisan db:seed --class=OrderSeeder
php artisan db:seed --class=CartSeeder
```

### Fresh Migration with Seeding
```bash
php artisan migrate:fresh --seed
```

## 📊 Data Created

After running all seeders, you'll have:
- **1 Admin user**
- **5 Regular users**
- **8 Services** (all active)
- **5-7 User locations** (distributed among users)
- **10-20 Orders** (2-4 per user with various statuses)
- **Multiple Cart items** (linked to accepted/rejected orders)

## 🔐 Test Credentials

### Admin Access
- **Email**: admin@ghaseel.com
- **Password**: password123
- **Phone**: +966501234567

### Regular User Access
- **Email**: ahmed@example.com
- **Password**: password123
- **Phone**: +966501111111

All other users use the same password: `password123`

## ⚠️ Important Notes

1. **Order of Execution**: Seeders must run in this order:
   - UserSeeder (creates users)
   - ServiceSeeder (creates services)
   - UserLocationSeeder (requires users)
   - OrderSeeder (requires users, services, and locations)
   - CartSeeder (requires orders)

2. **Database Reset**: If you want to start fresh:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Relationships**: All seeders maintain proper relationships:
   - Locations belong to users
   - Orders belong to users and locations
   - Orders have many services (many-to-many)
   - Carts belong to users and orders

## 🎯 Testing Scenarios

The seeders create data that covers various scenarios:

### Order Statuses
- **Pending**: Orders waiting for admin approval
- **Accepted**: Orders approved by admin (with final_price)
- **Rejected**: Orders rejected by admin (with rejection_reason)

### Order Processing Statuses
- **Processing**: Orders being worked on
- **Completed**: Orders finished
- **Delivered**: Orders delivered to customer

### Delivery Types
- **Normal**: Standard delivery
- **Express**: Fast delivery

## 📝 Customization

To customize the seeders:
1. Edit the respective seeder file in `database/seeders/`
2. Modify the data arrays
3. Run the seeder again

Example: To add more services, edit `ServiceSeeder.php` and add to the `$services` array.

