# Project Context

Last reviewed: 2026-07-07

## Stack
- Laravel ecommerce app.
- Main routes are in `routes/web.php`.
- Public Blade views are in `resources/views`.
- Admin Blade views are in `resources/views/admin`.
- Product images are stored under `public/img`.

## Current Features
- Public storefront: home, shop, product detail, contact, newsletter.
- Product browsing: category route `/shop/{category?}`, text search by name/description, price range filter, color filter.
- Cart: guest/session cart and user cart, add/update/remove/increment/decrement.
- Checkout: authenticated checkout, creates orders/order items, decrements stock.
- User: login, register, logout, forgot/reset password, profile update, password update.
- Customer area: wishlist, order list, order detail.
- Reviews: public review submission and admin approval.
- Admin: dashboard, products, categories, menus, orders/status, contacts, reviews, newsletters.

## Important Models
- `Product`: category, price, original_price, image, color JSON, sizes JSON, stock.
- `Category`: parent/children and product relationships.
- `CartItem`: product, user/session, quantity, color, size.
- `Order`: billing/shipping, payment_method, subtotal, shipping, total, status.
- `OrderItem`: product snapshot, price, quantity, color, size, total.
- `Review`, `Wishlist`, `Contact`, `Newsletter`, `Menu`, `User`.

## Recent Direction
- Extend ecommerce features:
  - Advanced product search, price filtering, sorting: implemented in `ShopController` and `resources/views/shop.blade.php`.
  - Coupon codes and discounts: implemented with `Coupon` model, admin coupon CRUD, checkout apply/remove, and order discount fields.
  - Online payment scaffold: implemented with `PaymentController` and `resources/views/payment.blade.php`.
  - Multiple product images: implemented with `ProductImage` model/table and admin product gallery upload.
  - Inventory visibility: product stock shows in admin/product cards and cart/checkout still enforce stock.
  - Admin revenue reports: implemented at route `admin.reports.revenue`.

## Notes For Future Work
- Avoid re-reading the whole project from scratch. Start from this file, then inspect only the files related to the requested feature.
- Product detail already expects `$product->images`; implement this through a model accessor/relationship.
- Existing checkout already validates stock and decrements it inside a DB transaction.
- Admin product create/edit already accepts primary image, color, sizes, and stock.
