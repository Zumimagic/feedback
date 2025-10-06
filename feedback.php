<?php include 'inc/header.php'; ?>

<?php
$records_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, $current_page); 
$offset = ($current_page - 1) * $records_per_page;

$count_sql = "SELECT COUNT(*) as total FROM feedback";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT * FROM feedback ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $records_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$feedback = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

   
  <h1>Past Feedback</h1>

  <?php if (empty($feedback)): ?>
    <p class="lead mt-3">There is no feedback</p>
  <?php endif; ?>

  <?php foreach ($feedback as $item): ?>
    <div class="card my-3 w-75">
     <div class="card-body text-center">
       <?php echo htmlspecialchars(ucfirst($item['body'])); ?>
       <div class="text-secondary mt-2">By <?php echo htmlspecialchars(ucwords($item[
         'name'
       ])); ?> on <?php echo date_format(
   date_create($item['date']),
   'g:ia \o\n l jS F Y'
 ); ?></div>
     </div>
   </div>
  <?php endforeach; ?>

  <?php if ($total_pages > 1): ?>
    <div class="custom-pagination mt-4">
      <div class="pagination-info">
        <span>Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></span>
        <span class="total-count">(<?php echo $total_records; ?> total feedback<?php echo $total_records != 1 ? 's' : ''; ?>)</span>
      </div>
      
      <div class="pagination-controls">
        <?php if ($current_page > 1): ?>
          <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-btn prev-btn" aria-label="Previous page">
            <i class="fas fa-chevron-left"></i>
            <span>Previous</span>
          </a>
        <?php else: ?>
          <span class="pagination-btn prev-btn disabled">
            <i class="fas fa-chevron-left"></i>
            <span>Previous</span>
          </span>
        <?php endif; ?>

        <?php if ($current_page < $total_pages): ?>
          <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-btn next-btn" aria-label="Next page">
            <span>Next</span>
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php else: ?>
          <span class="pagination-btn next-btn disabled">
            <span>Next</span>
            <i class="fas fa-chevron-right"></i>
          </span>
        <?php endif; ?>
      </div>
    </div>
  <?php endif; ?>

<?php include 'inc/footer.php'; ?>
