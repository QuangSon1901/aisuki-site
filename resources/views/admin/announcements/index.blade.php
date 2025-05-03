@extends('admin.layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Announcements</h5>
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Add Announcement
                    </a>
                </div>
                <div class="card-body">
                    @if(count($announcements) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="25%">Title</th>
                                        <th width="15%">Languages</th>
                                        <th width="15%">Period</th>
                                        <th width="10%" class="text-center">Status</th>
                                        <th width="10%" class="text-center">Priority</th>
                                        <th width="10%" class="text-center">Dismissible</th>
                                        <th width="10%" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $announcementData)
                                        <tr>
                                            <td>{{ $announcementData['announcement']->id }}</td>
                                            <td>
                                                <strong>{{ $announcementData['announcement']->title }}</strong>
                                            </td>
                                            <td>
                                                @foreach($announcementData['translations'] as $translation)
                                                    <span class="badge {{ $translation->language->is_default ? 'bg-success' : 'bg-primary' }} me-1">
                                                        {{ strtoupper($translation->language->code) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($announcementData['announcement']->start_date && $announcementData['announcement']->end_date)
                                                    {{ $announcementData['announcement']->start_date->format('M d, Y') }} - {{ $announcementData['announcement']->end_date->format('M d, Y') }}
                                                @elseif($announcementData['announcement']->start_date)
                                                    From {{ $announcementData['announcement']->start_date->format('M d, Y') }}
                                                @elseif($announcementData['announcement']->end_date)
                                                    Until {{ $announcementData['announcement']->end_date->format('M d, Y') }}
                                                @else
                                                    Always
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($announcementData['announcement']->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{ $announcementData['announcement']->priority }}
                                            </td>
                                            <td class="text-center">
                                                @if($announcementData['announcement']->is_dismissible)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-warning">No</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('admin.announcements.edit', $announcementData['announcement']->id) }}" class="btn btn-sm btn-info me-1" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.announcements.destroy', $announcementData['announcement']->id) }}" method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> No announcements found. Create your first announcement by clicking the "Add Announcement" button.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                this.submit();
            }
        });
    });
</script>
@endpush