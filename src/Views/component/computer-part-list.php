
<?php foreach($result as $part): ?>
<div class="card-body">
    <h5 class="card-title"><?= htmlspecialchars($part->getName()) ?></h5>
    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($part->getType()) ?> - <?= htmlspecialchars($part->getBrand()) ?></h6>
    <p class="card-text">
        <strong>Model:</strong> <?= htmlspecialchars($part->getModelNumber()) ?><br />
        <strong>Release Date:</strong> <?= htmlspecialchars($part->getReleaseDate()) ?><br />
        <strong>Description:</strong> <?= htmlspecialchars($part->getDescription()) ?><br />
        <strong>Performance Score:</strong> <?= htmlspecialchars($part->getPerformanceScore()) ?><br />
        <strong>Market Price:</strong> $<?= htmlspecialchars($part->getMarketPrice()) ?><br />
        <strong>RSM:</strong> $<?= htmlspecialchars($part->getRsm()) ?><br />
        <strong>Power Consumption:</strong> <?= htmlspecialchars($part->getPowerConsumptionW()) ?>W<br />
        <strong>Dimensions:</strong> <?= htmlspecialchars($part->getLengthM()) ?>m x <?= htmlspecialchars($part->getWidthM()) ?>m x <?= htmlspecialchars($part->getHeightM()) ?>m<br />
        <strong>Lifespan:</strong> <?= htmlspecialchars($part->getLifespan()) ?> years<br />
    </p>
    <p class="card-text"><small class="text-muted">Last updated on <?= htmlspecialchars($part->getTimeStamp()?->getUpdatedAt() ?? '') ?></small></p>
</div>
<?php endforeach; ?>


