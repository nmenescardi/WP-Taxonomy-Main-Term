
<?php wp_nonce_field($nonceAction, $nonceName); ?>
    
<select 
  name=<?= esc_attr($selectID) ?> 
  id=<?= esc_attr($selectName) ?>
>
  <option value="-1" >* Select a Term</option>

  <?php foreach ($terms as $term) : ?>

    <option 
      value=<?= esc_attr($term->term_id) ?>
      <?= selected($mainTerm, $term->term_id, false) ?>
    >
      <?= esc_attr($term->name) ?>
    </option>

  <?php endforeach; ?>

</select>
