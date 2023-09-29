			
			<?php 
			//check if behavioural aptitude is enable and display if true
			if ($enable_aptitudes == 'true') { ?>
				
				<table class="report_table template2">

					<thead> 
						<tr class="text-bold">
							<td colspan="7">
								BEHAVIOURAL APTITUDES (PSYCHOMOTOR & AFFECTIVE DOMAINS)
							</td>
						</tr>

						<tr class="">
							<th class="align_left">Aptitude</th>
							<th>5</th>
							<th>4</th>
							<th>3</th>
							<th>2</th>
							<th>1</th>
							<th>Rating Key</th>
						</tr>
					</thead>

					<tbody>
						
						<?php 
						$count = 1;
						//Aptitude Scores
						foreach ($aptitude_scores as $p) { ?>

							<tr>

								<?php
								$aptitude = $this->common_model->get_aptitude_details($p->aptitude_id)->aptitude;
								$score = $p->score; ?>

								<td class="align_left"><?php echo $aptitude; ?></td>

								<?php
								//5
								if ($score == 5) { ?>
									<td><i class="fa fa-check"></i></td>
								<?php } else { ?>
									<td class="empty"></td>
								<?php } ?>

								<?php
								//4
								if ($score == 4) { ?>
									<td><i class="fa fa-check"></i></td>
								<?php } else { ?>
									<td class="empty"></td>
								<?php } ?>

								<?php
								//3
								if ($score == 3) { ?>
									<td><i class="fa fa-check"></i></td>
								<?php } else { ?>
									<td class="empty"></td>
								<?php } ?>

								<?php
								//2
								if ($score == 2) { ?>
									<td><i class="fa fa-check"></i></td>
								<?php } else { ?>
									<td class="empty"></td>
								<?php } ?>

								<?php
								//1
								if ($score == 1) { ?>
									<td><i class="fa fa-check"></i></td>
								<?php } else { ?>
									<td class="empty"></td>
								<?php } ?>


								<?php
								//Rating Keys. Display only once
								if ($count == 1) { ?>

									<td rowspan="<?php echo count($aptitude_scores); ?>">
										<?php
										foreach ($aptitude_keys as $key => $value) { ?>

											<div class="">	
												<?php echo $key . ' - ' . $value; ?>
											</div>

										<?php } ?>				
									</td>

								<?php } ?>

							</tr>

							<?php $count++;

						} ?>

					</tbody>
				
				</table>
				

			<?php } ?>