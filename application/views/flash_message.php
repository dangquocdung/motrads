			<!-- Message -->
			<?php if ( $this->session->flashdata( 'success' )): ?>

				<div class="alert alert-success fade in">

					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					
					<?php echo $this->session->flashdata('success');?>

				</div>

			<?php elseif ( $this->session->flashdata( 'error' )): ?>

				<div class="alert alert-danger fade in">

					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>

					<?php echo $this->session->flashdata('error');?>
					
				</div>

			<?php endif; ?>