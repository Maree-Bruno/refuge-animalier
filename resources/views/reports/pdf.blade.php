<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport {{ $month }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            padding: 24px;
            background-color: #ffffff;
        }

        /* Header avec bordure en bas */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 16px;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #1f2937;
        }

        .export-info {
            font-size: 11px;
            color: #6b7280;
            padding: 8px 16px;
            background-color: #fef3c7;
            border-radius: 6px;
        }

        /* Section Stats avec les 4 cartes */
        .stats-section {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
            padding: 16px 0;
            margin-bottom: 24px;
        }

        .stat-card {
            flex: 1;
            min-width: 200px;
            max-width: 300px;
            border-radius: 16px;
            border: 4px solid;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 40px;
        }

        .stat-card.yellow {
            background-color: #fef3c7;
            border-color: #f59e0b;
        }

        .stat-card.green {
            background-color: #d1fae5;
            border-color: #10b981;
        }

        .stat-card.orange {
            background-color: #fed7aa;
            border-color: #f97316;
        }

        .stat-card.gray {
            background-color: #f3f4f6;
            border-color: #6b7280;
        }

        .stat-card .title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
        }

        .stat-card .number {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            color: #1f2937;
        }

        /* Sections d'animaux */
        .animal-section {
            margin-bottom: 32px;
            page-break-inside: avoid;
        }

        .animal-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }

        /* Grille d'animaux */
        .animals-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 16px;
        }

        /* Carte animal */
        .animal-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            page-break-inside: avoid;
        }

        .animal-card-content {
            display: flex;
            gap: 12px;
        }

        .animal-image {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .animal-info {
            flex: 1;
            min-width: 0;
        }

        .animal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .animal-name {
            font-weight: 600;
            font-size: 14px;
            color: #1f2937;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sex-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .animal-details {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .animal-details p {
            margin-bottom: 4px;
        }

        .animal-details strong {
            color: #374151;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid;
            margin-top: 8px;
        }

        .status-badge.adopted {
            background-color: rgba(209, 250, 229, 0.5);
            color: #065f46;
            border-color: #10b981;
        }

        .status-badge.validated {
            background-color: rgba(254, 215, 170, 0.2);
            color: #7c2d12;
            border-color: #f97316;
        }

        .status-badge.in-progress {
            background-color: rgba(224, 242, 254, 0.2);
            color: #075985;
            border-color: #0284c7;
        }

        /* Message vide */
        .no-data {
            text-align: center;
            padding: 32px;
            color: #6b7280;
            background-color: #f9fafb;
            border-radius: 8px;
            font-style: italic;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="header">
    <h2>Rapport de {{ $month }}</h2>
    <div class="export-info">
        📥 PDF généré le {{ $generatedAt }}
    </div>
</div>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stat-card yellow">
        <p class="title">Animaux adoptés</p>
        <p class="number">{{ $adoptedAnimals }}</p>
    </div>

    <div class="stat-card green">
        <p class="title">Animaux recueillis</p>
        <p class="number">{{ $refugedAnimals }}</p>
    </div>

    <div class="stat-card orange">
        <p class="title">En cours d'adoption</p>
        <p class="number">{{ $inProgress }}</p>
    </div>

    <div class="stat-card gray">
        <p class="title">Demande Acceptées</p>
        <p class="number">{{ $accepted }}</p>
    </div>
</section>

<!-- Animaux adoptés -->
@if(isset($animalsByStatus['Adopted']) && count($animalsByStatus['Adopted']) > 0)
    <section class="animal-section">
        <h3>Animaux adoptés</h3>
        <div class="animals-grid">
            @foreach($animalsByStatus['Adopted'] as $animal)
                <div class="animal-card">
                    <div class="animal-card-content">
                        @php
                            $imagePath = isset($animal->pictures[0])
                                ? public_path('images/animals/variants/300x300/' . $animal->pictures[0])
                                : public_path('images/billy.webp');
                        @endphp

                        @if(file_exists($imagePath))
                            <img src="{{ $imagePath }}" alt="{{ $animal->name }}" class="animal-image">
                        @else
                            <img src="{{ public_path('images/billy.webp') }}" alt="{{ $animal->name }}" class="animal-image">
                        @endif

                        <div class="animal-info">
                            <div class="animal-header">
                                <h4 class="animal-name">{{ $animal->name }}</h4>
                                <span class="sex-icon">
                                @if($animal->sex === 'male')
                                        ♂
                                    @else
                                        ♀
                                    @endif
                            </span>
                            </div>

                            <div class="animal-details">
                                <p><strong>Age :</strong> {{ $animal->age ?? 'N/A' }}</p>
                                <p><strong>Puce :</strong> {{ $animal->chip ?? 'N/A' }}</p>
                                <p><strong>Admission :</strong> {{ \Carbon\Carbon::parse($animal->admission_date)->format('d/m/Y') }}</p>
                            </div>

                            <span class="status-badge adopted">Adopted</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@else
    <section class="animal-section">
        <h3>Animaux adoptés</h3>
        <div class="no-data">
            Aucun animal enregistré pour cette période
        </div>
    </section>
@endif

<!-- Animaux au refuge -->
@if(isset($animalsByStatus['Validated']) && count($animalsByStatus['Validated']) > 0)
    <section class="animal-section">
        <h3>Animaux au refuge</h3>
        <div class="animals-grid">
            @foreach($animalsByStatus['Validated'] as $animal)
                <div class="animal-card">
                    <div class="animal-card-content">
                        @php
                            $imagePath = isset($animal->pictures[0])
                                ? public_path('images/animals/variants/300x300/' . $animal->pictures[0])
                                : public_path('images/billy.webp');
                        @endphp

                        @if(file_exists($imagePath))
                            <img src="{{ $imagePath }}" alt="{{ $animal->name }}" class="animal-image">
                        @else
                            <img src="{{ public_path('images/billy.webp') }}" alt="{{ $animal->name }}" class="animal-image">
                        @endif

                        <div class="animal-info">
                            <div class="animal-header">
                                <h4 class="animal-name">{{ $animal->name }}</h4>
                                <span class="sex-icon">
                                @if($animal->sex === 'male')
                                        ♂
                                    @else
                                        ♀
                                    @endif
                            </span>
                            </div>

                            <div class="animal-details">
                                <p><strong>Age :</strong> {{ $animal->age ?? 'N/A' }}</p>
                                <p><strong>Puce :</strong> {{ $animal->chip ?? 'N/A' }}</p>
                                <p><strong>Admission :</strong> {{ \Carbon\Carbon::parse($animal->admission_date)->format('d/m/Y') }}</p>
                            </div>

                            <span class="status-badge validated">Validated</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@else
    <section class="animal-section">
        <h3>Animaux au refuge</h3>
        <div class="no-data">
            Aucun animal enregistré pour cette période
        </div>
    </section>
@endif

<!-- Animaux en cours de validation -->
@if(isset($animalsByStatus['In progress']) && count($animalsByStatus['In progress']) > 0)
    <section class="animal-section">
        <h3>Animaux en cours de validation</h3>
        <div class="animals-grid">
            @foreach($animalsByStatus['In progress'] as $animal)
                <div class="animal-card">
                    <div class="animal-card-content">
                        @php
                            $imagePath = isset($animal->pictures[0])
                                ? public_path('images/animals/variants/300x300/' . $animal->pictures[0])
                                : public_path('images/billy.webp');
                        @endphp

                        @if(file_exists($imagePath))
                            <img src="{{ $imagePath }}" alt="{{ $animal->name }}" class="animal-image">
                        @else
                            <img src="{{ public_path('images/billy.webp') }}" alt="{{ $animal->name }}" class="animal-image">
                        @endif

                        <div class="animal-info">
                            <div class="animal-header">
                                <h4 class="animal-name">{{ $animal->name }}</h4>
                                <span class="sex-icon">
                                @if($animal->sex === 'male')
                                        ♂
                                    @else
                                        ♀
                                    @endif
                            </span>
                            </div>

                            <div class="animal-details">
                                <p><strong>Age :</strong> {{ $animal->age ?? 'N/A' }}</p>
                                <p><strong>Puce :</strong> {{ $animal->chip ?? 'N/A' }}</p>
                                <p><strong>Admission :</strong> {{ \Carbon\Carbon::parse($animal->admission_date)->format('d/m/Y') }}</p>
                            </div>

                            <span class="status-badge in-progress">In progress</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@else
    <section class="animal-section">
        <h3>Animaux en cours de validation</h3>
        <div class="no-data">
            Aucun animal enregistré pour cette période
        </div>
    </section>
@endif

<!-- Footer -->
<div class="footer">
    <p>Document généré automatiquement - {{ $generatedAt }}</p>
</div>
</body>
</html>
