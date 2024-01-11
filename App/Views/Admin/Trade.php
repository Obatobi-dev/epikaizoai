<?php $this->view("inc/head");?>
<style type="text/css">
    label {
        font-size: 0.8rem;
        font-weight: 650;
    }
    .sm-btn {
        padding: 0.6rem;
    }

    .form-group {
        margin: 0.5rem 0;
    }
    .nav-link {
        padding: 16px !important;
    }
    .nav-link.active {
        background: var(--color-primary) !important;
    }
</style>
<section id="wrapper">
    <aside id="dash__wrapper">
        <!-- Aside nav -->
        <?php $this->view("admin/aside");?>

        <section class="w-100">
            <!-- Top nav -->
            <?php $this->view("admin/nav");?>

            <!-- Main BEGIN -->
            <main id="main" class="w-100">
                <article class="container">
                    <div class="bg-primary d-block card text-end">
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#myModal" data-id="<?=\Model\Api::random(6);?>"><i class="fal fa-plus"></i> Add new</button>
                    </div>
                    <div class="table-responsive card bg-primary h-100">
                        <table class="table text-light">
                            <thead>
                                <tr class="text-cap">
                                    <th>name</th>
                                    <th>buy price</th>
                                    <th>sell price</th>
                                    <th>percentage change</th>
                                    <th>added</th>
                                    <th>last edit</th>
                                    <th>action</th>
                                </tr>
                            </thead>

                            <tbody id="list">
                                
                            </tbody>
                        </table>
                    </div>
                </article>
            </main>
            <!-- Main END -->
        </section>
    </aside>
</section>
<script>
$(()=>{
    if(SECURITY.length){
        SECURITY.map(DATA => {
            DETAIL = JSON.parse(DATA.detail)
            let inner = `
            <tr>
                <td>${DETAIL.name.toUpperCase()}</td>
                <td class="bg-success text-center">${DETAIL.buy_price}</td>
                <td class="bg-danger text-center">${DETAIL.sell_price}</td>
                <td>${DETAIL.percentage_change}</td>
                <td>${timeAgo(DATA.stamp)}</td>
                <td>${timeAgo(DETAIL.last_edit)}</td>
                <td><button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#myModal" data-id="${DATA.id}" onclick="editCoin('${DATA.id}','${DETAIL.buy_price}', '${DETAIL.sell_price}', '${DETAIL.percentage_change}', '${DETAIL.name}')">Update</button></</td>
            </tr>`
            $("#list").append(inner)
        })
    }

})
function editCoin(id, buy_price, sell_price, percentage_change, name){
    $("[name=id]").val(id)
    $("[name=buy_price]").val(buy_price)
    $("[name=sell_price]").val(sell_price)
    $("[name=percentage_change]").val(percentage_change)
    $("[name=name]").val(name)
}
</script>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal body -->
            <div class="modal-body bg-dark">
                <h3 class="text-light">Enter detail</h3>
                <hr>
                <div>
                    <form autocomplete="off">
                        <div class="input-group form-group active">
                            <span class="input-group-text bg-primary text-white"><i class="fal fa-coin"></i> <span class="px-2">Name</span></span>
                            <input class="form-control bg-none text-white" type="text" placeholder="Enter name" name="name">
                        </div>

                        <div class="input-group form-group active">
                            <span class="input-group-text bg-success text-white"><i class="fal fa-arrow-up"></i> <span class="px-2">Buy price</span></span>
                            <input class="form-control bg-none text-white" type="number" placeholder="0" name="buy_price" step="any">
                        </div>

                        <div class="input-group form-group active">
                            <span class="input-group-text bg-danger text-white"><i class="fal fa-arrow-down"></i> <span class="px-2">Sell price</span></span>
                            <input class="form-control bg-none text-white" type="number" placeholder="0" name="sell_price" step="any">
                        </div>

                        <div class="input-group form-group active">
                            <span class="input-group-text bg-primary text-white"><i class="fal fa-arrows-v"></i> <span class="px-2">% change</span></span>
                            <input class="form-control bg-none text-white" type="number" placeholder="0" name="percentage_change" step="any">
                        </div>

                        <button class="btn btn-primary w-100" onclick="APP.authentication(this)">
                            <span>Save</span>
                            <input type="hidden" name="list_coin">
                            <input type="hidden" name="id" value="">
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("[data-bs-target]").click(function(){
        let id = this.dataset.id
        $("[name=id]").val(id)
    })
</script>