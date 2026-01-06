<?php

namespace Drupal\ec_license_quantity;

use Drupal\commerce_order\OrderProcessorInterface;
use Drupal\commerce_order\Entity\OrderInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Decorates License Order Processor to allow quantity > 1.
 */
class LicenseOrderProcessorDecorator implements OrderProcessorInterface {

  /**
   * The decorated license order processor.
   */
  protected OrderProcessorInterface $innerProcessor;

  /**
   * The messenger service.
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs a new LicenseOrderProcessorDecorator.
   */
  public function __construct(
    OrderProcessorInterface $inner_processor,
    MessengerInterface $messenger
  ) {
    $this->innerProcessor = $inner_processor;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public function process(OrderInterface $order): void {
    // Store original quantities.
    $original_quantities = [];
    foreach ($order->getItems() as $order_item) {
      $original_quantities[$order_item->id()] = $order_item->getQuantity();
    }

    // Run original processor.
    $this->innerProcessor->process($order);

    // Restore quantities.
    foreach ($order->getItems() as $order_item) {
      $item_id = $order_item->id();

      if (isset($original_quantities[$item_id])) {
        $original_quantity = $original_quantities[$item_id];

        if ($original_quantity > 1 && $order_item->getQuantity() == 1) {
          $order_item->setQuantity($original_quantity);
        }
      }
    }

    // Remove all license warnings
    $this->messenger->deleteByType(MessengerInterface::TYPE_ERROR);
    $this->messenger->deleteByType(MessengerInterface::TYPE_WARNING);
  }

}
