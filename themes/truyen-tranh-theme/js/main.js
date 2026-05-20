/**
 * main.js - Truyen Tranh Theme
 * Trì hoãn hiển thị Skeleton đúng 3 giây kể từ khi nhìn thấy thẻ truyện,
 * sau đó hiển thị ảnh bìa thật (hoặc thông báo Chưa có ảnh) và thông tin chữ đồng bộ.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Tìm toàn bộ các phần tử bao bọc của truyện tranh trên trang chủ
    const truyenItems = document.querySelectorAll('.truyen-item');
    if (!truyenItems.length) return;

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                // Khi thẻ truyện lọt vào tầm nhìn thực tế của màn hình người dùng (cái nào bị hidden khỏi màn hình thì chưa load lazy loading vội)
                //thời gian loading ảnh Thumbnail tối thiểu là 1 giây để dễ trực quan tính năng của Code hơn
                // sau khi hết thời gian 1 giây thì ảnh Thumbnail lấy được từ Database về sẽ chiếm z-index của khu vực lazy loading
                if (entry.isIntersecting) {
                    const item = entry.target;
                    
                    // Huỷ theo dõi ngay lập tức để bộ đếm ngược không bị chạy lại khi cuộn trang lên xuống
                    observer.unobserve(item);
                    
                    // Đóng băng hiệu ứng Skeleton đúng 3 giây (3000ms) theo yêu cầu bài học
                    setTimeout(function () {
                        // 1. Gỡ bỏ hiệu ứng Shimmer chạy quét nền
                        const wrapper = item.querySelector('.truyen-thumb-wrapper');
                        if (wrapper) {
                            wrapper.classList.remove('skeleton');
                        }
                        
                        // 2. Xử lý nạp ảnh thực tế nếu bài viết có thiết lập ảnh bìa
                        const img = item.querySelector('img.delay-load');
                        if (img) {
                            const realSrc = img.getAttribute('data-src');
                            if (realSrc) {
                                img.src = realSrc;
                                img.removeAttribute('data-src');
                                img.style.opacity = '1'; // Kích hoạt hiệu ứng mờ dần vào (fade-in)
                            }
                        }
                        
                        // 3. Xử lý hiển thị thông báo "Chưa có ảnh" nếu bài viết không có ảnh bìa
                        const noThumb = item.querySelector('.no-thumb');
                        if (noThumb) {
                            noThumb.style.display = 'flex';//căn giữa ảnh 'không tìm thấy ảnh bìa'
                        }
                        
                        // 4. Cho hiển thị khối chữ thông tin truyện (Tiêu đề, Chương, Ngày đăng) đồng bộ mượt mà
                        item.classList.add('loaded');

                    }, 1000); 
                }
            });
        }, {
            rootMargin: '100px 0px', // Cho kích hoạt đếm trước khi cách màn hình 100px giúp trải nghiệm mượt mà
            threshold: 0.1
        });

        // Bật trình theo dõi cho từng thẻ truyện
        truyenItems.forEach(item => observer.observe(item));
    } else {
        // Fallback: Với trình duyệt quá cũ không hỗ trợ Observer, hiển thị toàn bộ lập tức không qua bộ đếm
        truyenItems.forEach(function (item) {
            const wrapper = item.querySelector('.truyen-thumb-wrapper');
            if (wrapper) wrapper.classList.remove('skeleton');
            
            const img = item.querySelector('img.delay-load');
            if (img && img.getAttribute('data-src')) {
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                img.style.opacity = '1';
            }
            
            const noThumb = item.querySelector('.no-thumb');
            if (noThumb) noThumb.style.display = 'flex';
            
            item.classList.add('loaded');
        });
    }
});