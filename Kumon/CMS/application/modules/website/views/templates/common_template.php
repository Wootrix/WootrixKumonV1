
<?php $this->load->view($header['view'],$header['data']); ?>

<?php $this->load->view($main_content['view'],$main_content['data']); ?>

<?php if($this->uri->segment(1) != '') { ?>
<?php $this->load->view($footer['view'],$footer['data']); ?>
<?php } ?>

