<section class="cookie" id="maintanace" style="background-color:dimgrey">
    <div class="row justify-content-center my-1">
        <div class="row">
            <div class="col-12">
                <p class="text-white text-center"> Maintanace </p>
            </div>
            <div class="col-12 text-center">
                <button type="button" onclick="removeMaintanance()" class="btn-7g">Close</button>
            </div>
        </div>
    </div>
</section>

<script>
    function removeMaintanance() {
        document.getElementById('maintanace').remove();
    }
</script>