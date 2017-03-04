<!-- Modal -->
<div class="modal" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1>Processing...</h1>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="complete"><i class="percent">0</i>% Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function progressBar(percent) {
        $('#pleaseWaitDialog .progress-bar').css('width', percent + '%');
        $('#pleaseWaitDialog .complete .percent').text(percent);
    }
</script>
