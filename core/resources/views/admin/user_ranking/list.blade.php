@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Icon')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('BV Left')</th>
                                    <th>@lang('BV Right')</th>
                                    <th>@lang('Bonus')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userRankings as $userRanking)
                                    <tr>
                                        <td><img src="{{ getImage(getFilePath('userRanking') . '/' . $userRanking->icon, getFileSize('userRanking')) }}"
                                                alt="ranking"></td>
                                        <td>{{ __($userRanking->level) }}</td>
                                        <td>{{ __($userRanking->name) }}</td>
                                        <td>{{ __($userRanking->bv_left) }}</td>
                                        <td>{{ __($userRanking->bv_right) }}</td>
                                        <td>{{ showAmount($userRanking->bonus) }}</td>
                                        <td>
                                            @php
                                                echo $userRanking->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editBtn me-1"
                                                data-icon="{{ getImage(getFilePath('userRanking') . '/' . $userRanking->icon, getFileSize('userRanking')) }}"
                                                data-ranking="{{ $userRanking }}"><i class="las la-pen"></i>@lang('Edit')</button>
                                            @if ($userRanking->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this ranking?')"
                                                    data-action="{{ route('admin.ranking.status', $userRanking->id) }}"><i
                                                        class="las la-eye-slash"></i>@lang('Disable')</button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this ranking?')"
                                                    data-action="{{ route('admin.ranking.status', $userRanking->id) }}"><i
                                                        class="las la-eye"></i>@lang('Enable')</button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div><!-- card end -->
        </div>
    </div>

    <div class="modal fade" id="rankingModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New User Ranking')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.ranking.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-5">
                                <div class="form-group">
                                    <label class="icon">@lang('Icon')</label>
                                    <x-image-uploader name="icon" image="" class="w-100" type="userRanking" :required=false />
                                </div>
                            </div>
                            <div class="col-xl-8 col-lg-7">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Level')</label>
                                            <input type="number" class="form-control" name="level" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Name')</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('BV Left')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="bv_left" min="0" class="form-control" required>
                                        <span class="input-group-text">@lang('BV')</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('BV Right')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="bv_right" min="0" class="form-control" required>
                                        <span class="input-group-text">@lang('BV')</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Bonus')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="bonus" min="0" class="form-control" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-outline--primary btn-sm modalShow addBtn" data-icon="{{ getImage(null, getFileSize('userRanking')) }}"><i
            class="las la-plus"></i> @lang('Add New')</button>
@endpush

@push('style')
    <style>
        .image-upload .thumb .profilePicPreview {
            height: 230px;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"
            let modal = $('#rankingModal');
            let action = `{{ route('admin.ranking.store') }}`;

            $('.addBtn').on('click', function() {
                modal.find('form').attr('action', action);
                modal.modal('show');
                modal.find('form')[0].reset();
                modal.find('.image-upload-preview').css('backgroundImage', `url(${$(this).data('icon')})`);
                modal.find('.icon').addClass('required');
                modal.find('[name=icon]').attr('required', 'required');
            });

            $('.editBtn').on('click', function() {
                let ranking = $(this).data('ranking');
                modal.find('[name=level]').val(ranking.level);
                modal.find('[name=name]').val(ranking.name);
                modal.find('[name=bv_left]').val(ranking.bv_left);
                modal.find('[name=bv_right]').val(ranking.bv_right);
                modal.find('[name=bonus]').val(parseFloat(ranking.bonus).toFixed(2));
                modal.find('.image-upload-preview').css('backgroundImage', `url(${$(this).data('icon')})`);
                modal.find('.icon').removeClass('required');
                modal.find('[name=icon]').removeAttr('required');

                modal.find('form').attr('action', `${action}/${ranking.id}`);
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
