<?php
defined('EVENT_ESPRESSO_VERSION') || exit;
/** @var string $event_permalink */
/** @var string $event_name */
/** @var string $event_description */
/** @var string $event_start */
/** @var string $event_end */
/** @var string $currency */
/** @var array $event_tickets */
/** @var string $venue_name */
/** @var string $venue_url */
/** @var string $venue_locality */
/** @var string $venue_region */
?>
<script type="application/ld+json">
{
  "@context": "http://schema.org/",
  "@type": "Event",
  "name": "<?php echo $event_name; ?>",
  "startDate": "<?php echo $event_start; ?>",
  "endDate": "<?php echo $event_end; ?>",
  "description": "<?php echo $event_description ?>",
  "url": "<?php echo $event_permalink; ?>",
  "offers": [
  <?php foreach ($event_tickets as $ticket) { ?>
    {
      "@type": "Offer",
      "url": "<?php echo $event_permalink ?>",
      "validFrom": "<?php echo $ticket['start_date'] ?>",
      "validThrough": "<?php echo $ticket['end_date'] ?>",
      "price": "<?php echo $ticket['price'] ?>",
      "priceCurrency": "<?php echo $currency ?>"
    }<?php if (is_array($event_tickets) && end($event_tickets) !== $ticket) { echo ','; }
    }
    ?>
    ]<?php if ($venue_name) { ?>,
  "location": {
    "@type": "Place",
    "name": "<?php echo $venue_name; ?>",
    "url": "<?php echo $venue_url ?>",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "<?php echo $venue_locality ?>",
      "addressRegion": "<?php echo $venue_region ?>"
    }
  }
  <?php } ?>
}
</script>