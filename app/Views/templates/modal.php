<!-- Modal Diagnosis-->
<div class="modal fade" id="modalDiagnosis" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleDiagnosis">Diagnosis Kelinci</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Gejala apa yang dialami kelinci anda?
            </div>
            <form action="/user/startDiagnosis/" method="post">
                <!-- <input type="hidden" id="idDeleteSample" name=" id_sample"> -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // get data
        //enable jika menggunakan modal
        // $('.btn-diagnosis').on('click', function() {
        $('.btn-diagnosis-enable').on('click', function() {
            const id_sample = $(this).data('sample');
            const kategori = $(this).data('kategori');
            const gejala = $(this).data('gejala');
            const penyakit = $(this).data('penyakit');
            //set data
            //$('#idDeleteSample').val(id_sample);
            //$('#titleDeleteSample').text("Hapus Sample " + kategori + ' ' + gejala + " = " + penyakit);
            //show modal
            //enable jika menggunakan modal
            $('#modalDiagnosis').modal('show');
        });
    });
</script>