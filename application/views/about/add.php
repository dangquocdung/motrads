			<?php
			$this->lang->load('ps', 'english');
			?>
			<ul class="breadcrumb">
				<li><a href="<?php echo site_url(). "/dashboard";?>"><?php echo $this->lang->line('dashboard_label')?></a> <span class="divider"></span></li>
				<li><?php echo $this->lang->line('add_about_button')?></li>
			</ul>
			<div class="wrapper wrapper-content animated fadeInRight">
			<?php
			$attributes = array('id' => 'about-form','enctype' => 'multipart/form-data');
			echo form_open(site_url('abouts/add'), $attributes);
			?>
				<legend><?php echo $this->lang->line('app_info_lable')?></legend>
				
				<div class="row">
					<div class="col-sm-8">
							<div class="form-group">
								<label><?php echo $this->lang->line('about_title_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_title_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input( array(
										'type' => 'text',
										'name' => 'title',
										'id' => 'title',
										'class' => 'form-control',
										'placeholder' => 'Title',
										'value' => $about->title
									));
								?>
							</div>
							
							<div class="form-group">
								<label><?php echo $this->lang->line('description_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_description_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<textarea class="form-control" name="description" placeholder="Description" rows="9"><?php echo $about->description; ?></textarea>
							</div>

							<div class="form-group">
								<label><?php echo $this->lang->line('about_email_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_email_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input( array(
										'type' => 'text',
										'name' => 'email',
										'id' => 'email',
										'class' => 'form-control',
										'placeholder' => 'Email',
										'value' => $about->email
									));
								?>
							</div>

							<div class="form-group">
								<label><?php echo $this->lang->line('about_phone_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_phone_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input( array(
										'type' => 'text',
										'name' => 'phone',
										'id' => 'phone',
										'class' => 'form-control',
										'placeholder' => 'Phone',
										'value' => $about->phone
									));
								?>
							</div>

							<div class="form-group">
								<label><?php echo $this->lang->line('about_website_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_website_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input( array(
										'type' => 'text',
										'name' => 'website',
										'id' => 'website',
										'class' => 'form-control',
										'placeholder' => 'Website',
										'value' => $about->website
									));
								?>
							</div>

							<div class="form-group">
								<label><?php echo $this->lang->line('about_decimal_label')?>
									<a href="#" class="tooltip-ps" data-toggle="tooltip" title="<?php echo $this->lang->line('about_website_tooltips')?>">
										<span class='glyphicon glyphicon-info-sign menu-icon'>
									</a>
								</label>
								<?php 
									echo form_input( array(
										'type' => 'text',
										'name' => 'price_decimal_place',
										'id' => 'price_decimal_place',
										'class' => 'form-control',
										'placeholder' => 'price_decimal_place',
										'value' => $about->price_decimal_place
									));
								?>
							</div>

					</div>
				</div>
				
				<hr/>
				
				<input type="submit" name="save" value="<?php echo $this->lang->line('save_button')?>" class="btn btn-primary"/>
				<input type="submit" name="gallery" value="<?php echo $this->lang->line('save_go_button')?>" class="btn btn-primary"/>
				<a href="<?php echo site_url('abouts');?>" class="btn btn-primary"><?php echo $this->lang->line('cancel_button')?></a>
			</form>
			</div>
			<script>
			$(document).ready(function(){
				$('#about-form').validate({
					rules:{
						title:{
							required: true,
							minlength: 4
						}
					},
					messages:{
						title:{
							required: "Please fill title.",
							minlength: "The length of title must be greater than 4"
						}
					}
				});
			});
			
			$(function () { $("[data-toggle='tooltip']").tooltip(); });
			
			</script>

