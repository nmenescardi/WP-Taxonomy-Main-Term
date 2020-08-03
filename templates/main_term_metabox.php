
<?php wp_nonce_field($nonceAction, $nonceName); ?>
    
<select 
  name=<?= $selectID ?> 
  id=<?= $selectName ?>
>
  <option value="-1" >* Select a Term</option>

  <?php foreach ($terms as $term) : ?>

    <option 
      value=<?= $term->term_id ?>
      <?= selected($mainTerm, $term->term_id, false) ?>
    >
      <?= $term->name ?>
    </option>

  <?php endforeach; ?>

</select>
