			
			<?php 
			//check if behavioural aptitude is enable and display if true
			if ($enable_aptitudes == 'true') { ?>
				
				<table class="report_table template2">

					<thead> 

						<tr class="text-bold">
							<td colspan="7">
								BEHAVIOURAL APTITUDES
							</td>
						</tr>

					</thead>

					<tbody>

						<tr class="text-bold">


							<?php 
							if (count($affective_aptitude_scores) > 0) { ?>

								<td style="padding: 10px 0;">	
									<table class="report_inner_table">
										<thead>

											<tr class="">
												<th class="align_left">Affective Domain</th>

												<?php
												if ($aptitude_display_type == 'list') { ?>
													
													<th class="min-w-70">Score</th>

												<?php } else { //grid ?>

													<th>5</th>
													<th>4</th>
													<th>3</th>
													<th>2</th>
													<th>1</th>

												<?php } ?>

											</tr>

										</thead>
										<tbody>
											
											<?php
											foreach ($affective_aptitude_scores as $p) { ?>

												<tr>

													<?php
													$aptitude = $p[0]; 
													$score = $p[1]; ?>

													<td class="align_left"><?php echo $aptitude; ?></td>

													<?php
													if ($aptitude_display_type == 'list') { ?>

														<td><?php echo $score; ?></td>

													<?php } else { ?>

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

													<?php } //end of aptitude display type check ?>

												</tr>

											<?php } ?>

										</tbody>
									</table>
								</td>

							<?php } ?>


							
							<?php 
							if (count($psychomotor_aptitude_scores) > 0) { ?>

								<td style="padding: 10px 0;">	
									<table class="report_inner_table">
										<thead>
											<tr class="">

												<th class="align_left">Psychomotor Domain</th>

												<?php
												if ($aptitude_display_type == 'list') { ?>
													
													<th class="min-w-70">Score</th>

												<?php } else { //grid ?>

													<th>5</th>
													<th>4</th>
													<th>3</th>
													<th>2</th>
													<th>1</th>

												<?php } ?>

											</tr>
										</thead>
										<tbody>
											
											<?php
											foreach ($psychomotor_aptitude_scores as $p) { ?>

												<tr>

													<?php
													$aptitude = $p[0]; 
													$score = $p[1]; ?>

													<td class="align_left"><?php echo $aptitude; ?></td>

													<?php
													if ($aptitude_display_type == 'list') { ?>

														<td><?php echo $score; ?></td>

													<?php } else { ?>

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

													<?php } //end of aptitude display type check ?>

												</tr>

											<?php } ?>

										</tbody>
									</table>
								</td>

							<?php } ?>
							


							<td>
								<p class="text-bold">Rating Key</p>
								<?php
								foreach ($aptitude_keys as $key => $value) { ?>

									<div class="text-normal">	
										<?php echo $key . ' - ' . $value; ?>
									</div>

								<?php } ?>				
							</td>



						</tr>

					</tbody>
				
				</table>
				

			<?php } ?>