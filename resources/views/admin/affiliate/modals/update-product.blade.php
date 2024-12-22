<div class="modal fade" id="editAffiliateProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cập nhật sản phẩm tiếp thị liên kết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.affiliate.update', $product->id) }}" method="post">
                    @csrf
                    @method('put')

                    <div class="validate-affiliate_discount">
                        <label class="form-label">
                            Hoa hồng (%)
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" class="form-control mb-2" name="affiliate_discount"
                            value="{{ $product->affiliate_discount }}">
                        <div class="text-danger feedback"></div>
                    </div>

                    <div class="validate-has_affiliate">
                        <label class="form-label">
                            Cho phép affiliate
                            <span class="text-danger">*</span>
                        </label>
                        <div class="form-check form-switch">
                            <input @if ($product->has_affiliate) checked @endif class="form-check-input"
                                type="checkbox" role="switch" value="1" name="has_affiliate" id="flexSwitchCheckDefault">
                        </div>
                        <div class="text-danger feedback"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary btn-save">Lưu lại</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(() => {
        const modal = $('#editAffiliateProductModal');
        const form = modal.find('form');
        const validate1 = form.find('.validate-affiliate_discount');
        const validate2= form.find('.validate-has_affiliate');

        modal.modal('show');

        modal.on('hidden.bs.modal', function() {
            clear();
        });

        modal.on('click', '.btn-save', function() {
            const formData = new FormData(form[0]);

            ajax(form.attr('action'), 'post', formData, function(res) {
                dispatchReloadEvent();
                toast(res.data.message);
                modal.modal('hide');
            }, function(e) {
                clearError();
                fillError(e.response.data);
            });
        });

        function clear() {
            form.find('input[name="affiliate_discount"]').val('');

            clearError();
        }

        function clearError() {
            validate1.find('.feedback').html('');
            validate1.find('input').removeClass('is-invalid');

            validate2.find('.feedback').html('');
            validate2.find('input').removeClass('is-invalid');
        }

        function fillError(e) {
            if (e.errors) {
                if (e.errors['affiliate_discount']) {
                    validate1.find('.feedback').html(e.errors['affiliate_discount'][0]);
                    validate1.find('input').addClass('is-invalid');
                }

                if (e.errors['has_affiliate']) {
                    validate2.find('.feedback').html(e.errors['has_affiliate'][0]);
                    validate2.find('input').addClass('is-invalid');
                }
            }
        }
    });
</script>
