 			<?php $this->lang->load('ps', 'vietnam'); ?>

			<ul class="breadcrumb">
				<li><a href="<?php echo site_url() . "/dashboard";?>"><?php echo $this->lang->line('dashboard_label')?></a> <span class="divider"></span></li>
				<li><?php echo $this->lang->line('shops_unpublish_list_label')?></li>
			</ul>
			
			<br/>
			
			<!-- Message -->
			<?php if($this->session->flashdata('success')): ?>
				<div class="alert alert-success fade in">
					<?php echo $this->session->flashdata('success');?>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			<?php elseif($this->session->flashdata('error')):?>
				<div class="alert alert-danger fade in">
					<?php echo $this->session->flashdata('error');?>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				</div>
			<?php endif;?>

			<div class="wrapper wrapper-content animated fadeInRight">
				<table class="table table-striped table-bordered">

					<tr>
						<th>No</th>
						<th><?php echo $this->lang->line( 'shop_name_label' ); ?></th>
						<th><?php echo $this->lang->line( 'publish_label' ); ?></th>
					</tr>

					<?php if ( ! $count=$this->uri->segment( 3 )) $count = 0; ?>

					<?php if ( isset( $shops ) && count( $shops->result() ) > 0 ): ?>

						<?php foreach ( $shops->result() as $shop ): ?>

						<tr>
							<td><?php echo ++$count; ?></td>
							<td><?php echo $shop->name; ?></td>
							<td>
								<button class="btn btn-sm btn-primary approve" 
								shopId='<?php echo $shop->id;?>'>Set As Publish</button>
								
							</td>
						</tr>

						<?php endforeach; ?>
					
					<?php else:?>

						<tr>
							<td colspan='7'>
							<span class='glyphicon glyphicon-warning-sign menu-icon'></span>
							<?php echo $this->lang->line('no_cat_data_message')?>
							</td>
						</tr>

					<?php endif; ?>

				</table>
			</div>

			<?php $this->pagination->initialize($pag); ?>
		
			<?php echo $this->pagination->create_links(); ?>

			<script>
			$(document).ready(function(){
				$(document).delegate('.approve','click',function(){
					
					var btn = $(this);
					var id = $(this).attr('shopId');
					$.ajax({
						url: '<?php echo site_url('shops/publish');?>/'+id,
						method:'GET',
						success:function(msg){
							if ( msg == 'true' ) {
								btn.parent().html('Shop has been published.');
								btn.remove();
							} else {
								alert('System error occured. Please contact your system administrator');
							}	
						}
					});
				});

				
			});
			</script>