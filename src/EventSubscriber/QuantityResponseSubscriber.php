<?php

namespace Drupal\ec_license_quantity\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Shows when cart items are updated (quantity increased).
 */
class QuantityResponseSubscriber implements EventSubscriberInterface {

  /**
   * The messenger service.
   */
  protected MessengerInterface $messenger;

  /**
   * Constructs a new QuantityResponseSubscriber.
   */
  public function __construct(
    MessengerInterface $messenger
  ) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::RESPONSE => ['onResponse', -49],
    ];
  }

  /**
   * Clean messages.
   */
  public function onResponse(ResponseEvent $event): void {
    // Remove ALL warnings and errors.
    $this->messenger->deleteByType(MessengerInterface::TYPE_WARNING);
    $this->messenger->deleteByType(MessengerInterface::TYPE_ERROR);

    // Remove messages from request attributes if it uses.
    $request = $event->getRequest();
    $request->attributes->remove('ec_cart_enhancements_messages');

    $response = $event->getResponse();
    if (!$response instanceof AjaxResponse) {
      return;
    }

    $purchased_entity = &ec_license_quantity_entity_cache();
    if (!$purchased_entity) {
      return;
    }

    $purchased_entity = NULL;
  }

}
