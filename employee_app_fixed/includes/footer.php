    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!-- Additional JavaScript -->
    <?php if (isset($extra_js) && is_array($extra_js)): ?>
        <?php foreach ($extra_js as $js_file): ?>
            <script src="<?php echo htmlspecialchars($js_file); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline JavaScript -->
    <?php if (isset($inline_js) && !empty($inline_js)): ?>
        <script>
            <?php echo $inline_js; ?>
        </script>
    <?php endif; ?>

    <!--
        includes/footer.php
        - ปลายทางของทุกหน้า (ปิด tag body/html)
        - จุดนี้เป็นที่เหมาะสำหรับเพิ่มสคริปต์เฉพาะหน้าด้วยตัวแปร $extra_js หรือ $inline_js
        - โปรดระวังการ echo โค้ดจากตัวแปรตรงๆ หากข้อมูลมาจากผู้ใช้ ให้ sanitize ก่อน
    -->
</body>
</html>
