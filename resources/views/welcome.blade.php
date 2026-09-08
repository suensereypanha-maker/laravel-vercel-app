<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel on Vercel + TiDB MySQL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .card {
            background-color: #1e293b;
            border: 1px solid #334155;
            color: #f8fafc;
        }
        .form-control {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }
        .form-control:focus {
            background-color: #0f172a;
            border-color: #38bdf8;
            color: #f8fafc;
            box-shadow: 0 0 0 0.25rem rgba(56, 189, 248, 0.25);
        }
        .badge-tidb {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
        }
    </style>
</head>
<body class="py-5">
    <div class="container" style="max-width: 720px;">
        <!-- Header -->
        <div class="text-center mb-4">
            <h1 class="fw-bold mb-2">Laravel on Vercel</h1>
            <p class="text-secondary mb-3">Serverless Laravel connected to TiDB Cloud MySQL</p>
            <div class="d-flex justify-content-center gap-2">
                @if($dbConnected)
                    <span class="badge badge-tidb px-3 py-2 rounded-pill"><i class="bi bi-database-check me-1"></i> TiDB MySQL Connected</span>
                @else
                    <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-database-x me-1"></i> DB Not Connected</span>
                @endif
                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-cloud-check me-1"></i> Vercel Live</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-25 text-success-emphasis mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger-emphasis mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if(!$dbConnected && isset($dbError))
            <div class="alert alert-warning border-0 bg-warning bg-opacity-25 text-warning-emphasis mb-4">
                <div class="fw-bold"><i class="bi bi-exclamation-circle-fill me-1"></i> Database Notice:</div>
                <small class="font-monospace d-block mt-1">{{ $dbError }}</small>
                <small class="d-block mt-2">Make sure to add your <code>DB_*</code> environment variables in Vercel Project Settings.</small>
            </div>
        @endif

        <!-- Create Category Form -->
        <div class="card shadow-sm mb-4 rounded-4">
            <div class="card-body p-4">
                <h5 class="card-title fw-semibold mb-3"><i class="bi bi-plus-circle me-2 text-info"></i> Create New Category</h5>
                <form action="/categories" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label text-secondary small">Category Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="e.g. Technology, Books, Music" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label text-secondary small">Description (optional)</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Brief description...">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                        <i class="bi bi-save me-1"></i> Save to Database
                    </button>
                </form>
            </div>
        </div>

        <!-- Category List -->
        <div class="card shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-semibold mb-0"><i class="bi bi-tags me-2 text-warning"></i> Categories</h5>
                    <span class="badge bg-secondary rounded-pill">{{ $categories->count() }} items</span>
                </div>

                @forelse($categories as $category)
                    <div class="p-3 mb-2 rounded-3 bg-dark bg-opacity-50 border border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold text-white">{{ $category->name }}</div>
                            @if($category->description)
                                <small class="text-secondary">{{ $category->description }}</small>
                            @endif
                        </div>
                        <span class="badge bg-dark text-secondary border border-secondary border-opacity-25 small">#{{ $category->id }}</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-secondary">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-muted"></i>
                        No categories found in database yet. Add one above!
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>