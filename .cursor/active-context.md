> **BrainSync Context Pumper** 🧠
> Dynamically loaded for active file: `themes\truyen-tranh-theme\index.php` (Domain: **Generic Logic**)

### 📐 Generic Logic Conventions & Fixes
- **[what-changed] Replaced auth Unknown**: -     <div class="manga-banner-bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-157
+     <div class="manga-banner-bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop'); ?>');"></div>
-     // 
+ </div>
-     // 632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop'); ?>');"></div>
+ 
- </div>
+ <div class="container">
- 
+     <div class="manga-header-wrapper">
- <div class="container">
+         <div class="manga-cover">
-     <div class="manga-header-wrapper">
+             <?php if (has_post_thumbnail()): ?>
-         <div class="manga-cover">
+                 <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
-             <?php if (has_post_thumbnail()): ?>
+             <?php else: ?>
-                 <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
+                 <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="<?php the_title(); ?>">
-             <?php else: ?>
+             <?php endif; ?>
-                 <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="<?php the_title(); ?>">
+         </div>
-             <?php endif; ?>
+         
-         </div>
+         <div class="manga-info-top">
-         
+             <h1 class="manga-title"><?php the_title(); ?></h1>
-         <div class="manga-info-top">
+             
-             <h1 class="manga-title"><?php the_title(); ?></h1>
+             <div class="manga-author">
-             
+                 <?php
-             <div class="manga-author">
+                 $author = get_post_meta(get_the_ID(), '_manga_author', true);
-                 <?php
+           
… [diff truncated]
- **[what-changed] Replaced auth Unknown**: -     <div class="manga-banner-bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop'); ?>');"></div>
+     <div class="manga-banner-bg" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full') ?: 'https://images.unsplash.com/photo-157
- </div>
+     // 
- 
+     // 632767115-351597cf2477?q=80&w=1200&auto=format&fit=crop'); ?>');"></div>
- <div class="container">
+ </div>
-     <div class="manga-header-wrapper">
+ 
-         <div class="manga-cover">
+ <div class="container">
-             <?php if (has_post_thumbnail()): ?>
+     <div class="manga-header-wrapper">
-                 <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
+         <div class="manga-cover">
-             <?php else: ?>
+             <?php if (has_post_thumbnail()): ?>
-                 <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="<?php the_title(); ?>">
+                 <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
-             <?php endif; ?>
+             <?php else: ?>
-         </div>
+                 <img src="https://images.unsplash.com/photo-1578632767115-351597cf2477?q=80&w=400&auto=format&fit=crop" alt="<?php the_title(); ?>">
-         
+             <?php endif; ?>
-         <div class="manga-info-top">
+         </div>
-             <h1 class="manga-title"><?php the_title(); ?></h1>
+         
-             
+         <div class="manga-info-top">
-             <div class="manga-author">
+             <h1 class="manga-title"><?php the_title(); ?></h1>
-                 <?php
+             
-                 $author = get_post_meta(get_the_ID(), '_manga_author', true);
+             <div class="manga-author">
-                 echo $author ? esc_
… [diff truncated]
