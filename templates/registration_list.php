<table class="w-100 m-auto">
	 <?php if(count($data['items']) > 0): ?>
		<tr>	 
		  <?php foreach($data['items'][0] as $key => $value): ?>
		    <th class="border border-dark p-3 font-weight-bold text-center"><?= $key ?></th>
		  <?php endforeach ?>
		</tr>
		<?php foreach($data['items'] as $item): ?>
		  <tr>
		    <?php foreach($item as $key => $field): ?>
		      <td class="border p-3">
		        <?php if($key == 'rubrics'): ?>
		          <ul class="list-unstyled">
		            <?php $rubrics_arr = explode('***', $field) ?>
		            <?php foreach($rubrics_arr as $key => $rubric): ?>
		              <li class="p-1 pr-2 pl-2 m-1 btn btn-primary d-block">
		                <?= $rubric ?>
		              </li>
		            <?php endforeach ?>
		          </ul>
		        <?php else: ?>
		          <?= $field ?>
		        <?php endif ?>
		      </td>
		    <?php endforeach ?>
		    <td class="border p-3">
		    	<button class="delete-registration btn btn-danger" data-reg_id="<?= $item['id'] ?>" data-reg_type="<?= $data['type'] ?>">
		    		Удалить
		    	</button>
		    </td>
		  </tr>
		<?php endforeach ?>
	<?php endif; ?>
</table>
