<div class="container">
    <div class="row-fluid">
        <div class="span12">
            <? $this->load->view('admin/admin_menu-inc');?>
        </div>
    </div>
    <div class="row-fluid">
       <div class="span12">
           <h1>Logs</h1>
       </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                  <tr>
                    <th>User</th>
                    <th>Date</th>
                    <th>IP Address</th>
                    <th>Agent</th>
                    <th>Platform</th>
                  </tr>
                </thead>
                <tbody>
                    <?foreach ($logs as $log) {?>
                    <tr title="log_id=<?=$log->log_id?>">
                        <td><?=$log->user_lastname?> <?=$log->user_firstname?></td>
                        <td><?=$log->log_date?></td>
                        <td><?=$log->log_ip?></td>
                        <td><?=$log->log_agent?></td>
                        <td><?=$log->log_platform?></td>
                    </tr>
                    <?}?>
                </tbody>
              <caption><?=$pagination?></caption>
            </table>
            
        </div>
    </div>
</div>