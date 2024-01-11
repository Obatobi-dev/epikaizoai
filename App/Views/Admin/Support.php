<?php $this->view("inc/head");?>
<style type="text/css">
	.w-icon {
		font-size: 26px;
	}
	table {
		margin-bottom: 0 !important;
/*		border-radius: 10px !important;*/
	}
	table::scrollbar {
		width: 4px;
	}
	td {
		word-wrap: break-word;
		white-space: nowrap;
	}
	tbody td {
		font-size: 12px;
	}
	.btn-xs {
	    padding: 0.1875rem;
	    width: 1.625rem;
	    height: 1.625rem;
	    min-width: 1.625rem;
	    min-height: 1.625rem;
	}
</style>
<section id="wrapper">
	<aside id="dash__wrapper">
		<!-- Aside nav -->
		<?php $this->view("admin/aside");?>

		<section class="w-100">
			<!-- Top nav -->
			<?php $this->view("admin/nav");?>

			<!-- MAIN -->
			<main id="main" class="w-100">
			    <article class="row" id="supportHistory_"></article>
			</main>
		</section>
	</aside>
</section>
<script>
    fetch(`${ORIGIN}/json/v2/support`)
    .then(res => {return res.json()})
    .then(DATA => {
        DATA.map(SUPPORT => {
            let detail = JSON.parse(SUPPORT.detail)
            let haveAttachment = ``
            if(detail.attachment != null){
                haveAttachment = `<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#myModal" onclick="attachmentView('${detail.attachment}')"><i class="fal fa-file-alt"></i></button>`
            }
            let inner = `
            <div class="col-md-6">
	            <div class="card">
	                <div class="d-flex align-items-center justify-content-between">
	                    <small class="d-block">${detail.subject}</small>
	                    <small class="d-block">${timeAgo(SUPPORT.stamp, SYSTEM_TZ)}</small>
	                </div>
	                <p class="text-dark">${detail.message}</p>
	                <div>
	                    ${haveAttachment}
	                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#myModal" onclick="replyModal('${SUPPORT.id}')"><i class="fal fa-comment-dots"></i></button>
	                </div>
	            </div>
	        </div>
            `
            $("#supportHistory_").append(inner)
        })
    })
    
    function attachmentView(attachment){
		$("#attachmentView_").html(
			`<div class="modal-body">
				<img src="${ORIGIN}${attachment}" class="img-thumbnail img-fluid">
			</div>
			`
		)
	}
	
	function replyModal(id){
	    $("#attachmentView_").html(
	        `
	        <form id="supportReply_" class="p-3" onsubmit="return false;">
	            <h3>Reply to</h3>
	            <p></p>
	            <div class="form-group">
	                <textarea class="form-control" rows="4" placeholder="Enter message" name="reply"></textarea>
	            </div>
	            
	            <button class="btn btn-primary" onclick="ADMIN.replyToSupport(this)" form="supportReply_">
	                <input type="hidden" name="replyToSupprt">
	                <input type="hidden" name="id" value="${id}">
	                <span>Send reply</span>
	            </button>
	        </form>
	        `
	   )
	}
</script>
<!-- The Modal -->
<div class="modal fade" id="myModal">
	<div class="modal-dialog">
		<div class="modal-content" id="attachmentView_">
		</div>
	</div>
</div>
