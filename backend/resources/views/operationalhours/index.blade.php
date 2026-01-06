@extends('adminlte::page')

@section('title', 'Operational Hours')

@section('content_header')
<h1>Manage Operational Hours</h1>
@stop

@section('content')
<div class="container-fluid">
    <!-- service Type Filter -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="serviceTypeFilter" class="form-label">service Type</label>
            <select id="serviceTypeFilter" class="form-select">
                <option value="">All</option>
                <option value="store">Store</option>
                <option value="website">Website</option>
                <!-- Tambahkan opsi lain jika diperlukan -->
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Operational Hours</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" id="operationalHoursTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Day</th>
                                <th>Opening Time</th>
                                <th>Closing Time</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Diisi via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
{{-- Tambahkan jika perlu custom CSS --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Debounce utility
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    const serviceTypeFilter = document.getElementById('serviceTypeFilter');
    const tableBody = document.querySelector('#operationalHoursTable tbody');

    const loadOperationalHours = debounce(function() {
        const serviceType = serviceTypeFilter.value;

        axios.get("{{ route('operational-hours.filter') }}", {
                params: {
                    service_type: serviceType || null
                }
            })
            .then(response => {
                const data = response.data;
                let html = '';

                if (data.length === 0) {
                    html = `<tr><td colspan="7" class="text-center">No operational hours found</td></tr>`;
                } else {
                    data.forEach(hour => {
                        const openTime = hour.open_time || '-';
                        const closeTime = hour.close_time || '-';
                        const createdAt = hour.created_at ? new Date(hour.created_at).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        }) : '-';

                        html += `
                        <tr>
                            <td>${hour.id}</td>
                            <td>${hour.day || '-'}</td>
                            <td>${openTime}</td>
                            <td>${closeTime}</td>
                            <td>
                                <span class="badge ${hour.status === 'open' ? 'bg-success' : 'bg-danger'}">
                                    ${hour.status ? hour.status.charAt(0).toUpperCase() + hour.status.slice(1) : '-'}
                                </span>
                            </td>
                            <td>${createdAt}</td>
                            <td>
                                <a href="/admin/operational-hours/${hour.id}/edit" class="btn btn-sm btn-primary">Edit
                                </a>
                                <form action="/admin/operational-hours/${hour.id}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    });
                }

                tableBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center">Error loading data</td></tr>`;
            });
    }, 300);

    // Load saat halaman dibuka & saat filter berubah
    serviceTypeFilter.addEventListener('change', loadOperationalHours);
    loadOperationalHours(); // initial load
</script>
@stop