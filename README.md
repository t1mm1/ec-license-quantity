# EC License Quantity

A Drupal Commerce module that enables quantity management for licensed products. By default, Commerce License restricts licensed product variations to a quantity of 1 in the cart. This module removes that limitation, allowing customers to purchase multiple quantities of the same licensed product.

## The Problem

Commerce License automatically enforces a quantity of 1 for licensed products in the cart. When customers try to add the same licensed product multiple times or increase the quantity, the system resets it back to 1 and displays warning messages. This behavior prevents scenarios where customers need to purchase multiple licenses of the same product (e.g., multiple seats of software, multiple course enrollments, etc.).

## The Solution

This module decorates the Commerce License order processor and intercepts quantity validation to:
- Allow adding licensed products with quantity > 1
- Support natural cart behavior: first add sets quantity to 1, subsequent adds increment the quantity
- Enable quantity changes directly in the cart
- Remove restrictive warning and error messages
- Maintain compatibility with the standard Commerce License workflow

## Features

- Seamless quantity management for licensed products
- Natural cart increment behavior
- Supports quantity changes in cart view
- Removes Commerce License quantity restrictions
- Clean message handling without confusing warnings
- Works with both single and multiple license types

## Requirements

- Drupal: ^10 || ^11
- Commerce License
- Commerce Order

## Installation

1. Download and extract the module to your `modules/custom` directory
2. Enable the module:
   ```bash
   drush en ec_license_quantity
   ```
   Or via the UI: Administration → Extend

## Configuration

No configuration needed. The module works automatically once enabled.

## Usage

Once enabled, the module allows customers to:
1. Add licensed products to cart with any quantity
2. Update quantities in the cart
3. Add the same licensed product multiple times (quantity will increment)

The system will generate multiple licenses based on the quantity purchased when the order is completed.

## How It Works

### Order Processor Decorator

The `LicenseOrderProcessorDecorator` decorates Commerce License's order processor:
- Stores original quantities before processing
- Runs the standard license processor
- Restores quantities that were reset to 1
- Removes unnecessary warning messages

### Response Subscriber

The `QuantityResponseSubscriber` cleans up messages during AJAX responses to provide a smooth user experience without confusing warnings.

## Technical Details

### Key Components

- **LicenseOrderProcessorDecorator.php**: Decorates the multiple license order processor to preserve quantities
- **QuantityResponseSubscriber.php**: Event subscriber that removes restrictive messages
- **ec_license_quantity.services.yml**: Service definitions with decoration priority

### Service Decoration

The module uses Drupal's service decoration pattern to wrap `commerce_license.multiple_license_order_processor` with a decoration priority of 100, ensuring it runs before other decorators.

## Troubleshooting

### Popup not appearing
- Ensure all required modules are enabled
- Clear Drupal cache

## Use Cases

This module is ideal for scenarios where you need to sell multiple licenses as a single purchase:
- Software licenses (e.g., "Buy 5 seats")
- Course enrollments (e.g., "Register 3 students")
- Membership plans (e.g., "Add 10 team members")
- Digital content access (e.g., "Purchase 2 copies")

## Important Notes

- Each purchased quantity will generate a separate license entity
- License assignment and management follow standard Commerce License behavior
- The module preserves all Commerce License functionality while removing only the quantity restriction

## Compatibility

Tested with:
- Drupal 10.x and 11.x
- Commerce License 3.x
- Commerce Order 3.x

## Support and Contribution

This is a custom module. For issues or feature requests, please contact your development team or module maintainer.

## License

This module follows the same license as Drupal core (GPL v2 or later).

## Credits

Developed for Drupal Commerce 3.x and Drupal 10/11.

## Author

Pavel Kasianov.

Linkedin: https://www.linkedin.com/in/pkasianov/</br>
Drupal org: https://www.drupal.org/u/pkasianov
