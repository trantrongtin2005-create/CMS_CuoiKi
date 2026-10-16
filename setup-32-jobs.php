<?php
/**
 * Setup 32 Real Jobs (4 distinct jobs per each of the 8 categories)
 */

require_once( '/var/www/html/wp-load.php' );

$categories_data = array(
    'cong-nghe-thong-tin' => array(
        'name' => 'Công nghệ thông tin (IT)',
        'jobs' => array(
            array(
                'title' => 'Kỹ Sư Lập Trình Backend Java / Spring Boot',
                'company' => 'FPT Software',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/fpt-logo.jpg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '25.000.000 VNĐ - 38.000.000 VNĐ',
                'bullets' => array(
                    'Thiết kế và phát triển kiến trúc hệ thống Microservices quy mô lớn sử dụng Spring Boot và Docker.',
                    'Tối ưu hóa cơ sở dữ liệu quan hệ và NoSQL, xử lý hàng triệu giao dịch hiệu năng cao mỗi ngày.',
                    'Phối hợp cùng đội ngũ kỹ sư quốc tế trong các dự án chuyển đổi số công nghệ đám mây AWS và Azure.'
                )
            ),
            array(
                'title' => 'Senior Frontend Developer (ReactJS / Next.js)',
                'company' => 'VNG Corporation',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vng-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '28.000.000 VNĐ - 42.000.000 VNĐ',
                'bullets' => array(
                    'Xây dựng giao diện ứng dụng web hiện đại, mượt mà với ReactJS, Next.js và TailwindCSS.',
                    'Tối ưu hóa tốc độ tải trang, Core Web Vitals và nâng cao trải nghiệm người dùng trên đa nền tảng.',
                    'Làm việc chặt chẽ với đội ngũ UI/UX Designer và Backend Engineer để tích hợp API thời gian thực.'
                )
            ),
            array(
                'title' => 'Kỹ Sư Phát Triển Mobile App (Flutter / React Native)',
                'company' => 'Ví Điện Tử MoMo',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/momo-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '26.000.000 VNĐ - 40.000.000 VNĐ',
                'bullets' => array(
                    'Phát triển và bảo trì các tính năng thanh toán tài chính an toàn trên ứng dụng di động MoMo.',
                    'Tích hợp các cổng thanh toán điện tử, quét mã QR và bảo mật xác thực sinh trắc học cao cấp.',
                    'Đảm bảo ứng dụng hoạt động ổn định, mượt mà trên cả hai hệ điều hành iOS và Android.'
                )
            ),
            array(
                'title' => 'Chuyên Viên An Toàn Thông Tin & Cloud DevOps',
                'company' => 'Tập Đoàn Viễn Thông Viettel',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/viettel-logo.svg',
                'location' => 'Hà Nội',
                'salary' => '30.000.000 VNĐ - 45.000.000 VNĐ',
                'bullets' => array(
                    'Quản trị, vận hành và giám sát hạ tầng điện toán đám mây Kubernetes, CI/CD tự động hóa.',
                    'Thiết lập các chính sách bảo mật mạng, phòng chống tấn công DDoS và rà quét lỗ hổng bảo mật định kỳ.',
                    'Đảm bảo hệ thống đạt chuẩn an ninh thông tin quốc tế ISO 27001 và vận hành liên tục 24/7.'
                )
            )
        )
    ),
    'giao-duc-dao-tao' => array(
        'name' => 'Giáo dục & Đào tạo',
        'jobs' => array(
            array(
                'title' => 'Giảng Viên Tiếng Anh IELTS Senior (Band 8.0+)',
                'company' => 'Hệ Thống Anh Văn Hội Việt Mỹ VUS',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vus-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '22.000.000 VNĐ - 35.000.000 VNĐ',
                'bullets' => array(
                    'Trực tiếp giảng dạy các khóa luyện thi IELTS chuyên sâu từ Band 5.5 đến Band 8.0+ cho học viên.',
                    'Áp dụng phương pháp giảng dạy tương tác quốc tế, theo sát tiến độ và hỗ trợ học viên đạt mục tiêu.',
                    'Tham gia biên soạn tài liệu giảng dạy, ngân hàng đề thi thử chuẩn Cambridge và nâng cao kỹ năng sư phạm.'
                )
            ),
            array(
                'title' => 'Giáo Viên Khoa Học Tự Nhiên & STEM Song Ngữ',
                'company' => 'Hệ Thống Giáo Dục Vinschool',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vingroup-logo.jpg',
                'location' => 'Hà Nội',
                'salary' => '20.000.000 VNĐ - 30.000.000 VNĐ',
                'bullets' => array(
                    'Giảng dạy môn Khoa học tự nhiên và các chuyên đề STEM theo khung chương trình song ngữ quốc tế.',
                    'Hướng dẫn học sinh thực hiện các dự án nghiên cứu khoa học thực tế và tham gia các cuộc thi sáng tạo.',
                    'Tổ chức hoạt động trải nghiệm thực hành trong phòng thí nghiệm hiện đại đạt tiêu chuẩn quốc tế.'
                )
            ),
            array(
                'title' => 'Giảng Viên Công Nghệ Thông Tin & Trí Tuệ Nhân Tạo',
                'company' => 'Trường Đại Học FPT',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/fpt-logo.jpg',
                'location' => 'Đà Nẵng',
                'salary' => '25.000.000 VNĐ - 38.000.000 VNĐ',
                'bullets' => array(
                    'Giảng dạy các học phần Lập trình nâng cao, Cấu trúc dữ liệu và Nhập môn Trí tuệ nhân tạo (AI).',
                    'Hướng dẫn sinh viên thực hiện đồ án tốt nghiệp gắn liền với nhu cầu thực tế của các tập đoàn công nghệ.',
                    'Tham gia các công trình nghiên cứu khoa học công bố trên các tạp chí và hội thảo học thuật uy tín.'
                )
            ),
            array(
                'title' => 'Chuyên Viên Phát Triển Chương Trình Đào Tạo Quốc Tế',
                'company' => 'Tổ Chức Giáo Dục Quốc Tế ILA',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vus-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '22.000.000 VNĐ - 32.000.000 VNĐ',
                'bullets' => array(
                    'Nghiên cứu xu hướng giáo dục hiện đại và cải tiến khung chương trình đào tạo tiếng Anh trẻ em và người lớn.',
                    'Tổ chức các buổi tập huấn định kỳ về nghiệp vụ sư phạm và phương pháp giảng dạy mới cho giáo viên.',
                    'Đánh giá hiệu quả học tập của học viên và đề xuất các giải pháp nâng cao chất lượng dịch vụ giáo dục.'
                )
            )
        )
    ),
    'ke-toan-tai-chinh' => array(
        'name' => 'Kế toán / Tài chính',
        'jobs' => array(
            array(
                'title' => 'Kế Toán Trưởng Tập Đoàn (Chief Accountant)',
                'company' => 'Tập Đoàn Vingroup',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vingroup-logo.jpg',
                'location' => 'Hà Nội',
                'salary' => '45.000.000 VNĐ - 65.000.000 VNĐ',
                'bullets' => array(
                    'Chịu trách nhiệm toàn diện về công tác tài chính, kế toán và quyết toán thuế hợp nhất của tập đoàn.',
                    'Thiết lập, hoàn thiện hệ thống quy chế tài chính và kiểm soát rủi ro dòng tiền theo chuẩn mực IFRS.',
                    'Làm việc trực tiếp với các cơ quan kiểm toán quốc tế Big4, ngân hàng và cơ quan thanh tra thuế nhà nước.'
                )
            ),
            array(
                'title' => 'Chuyên Viên Cao Cấp Phân Tích Tài Chính & Đầu Tư',
                'company' => 'Công Ty Cổ Phần FPT',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/fpt-logo.jpg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '25.000.000 VNĐ - 38.000.000 VNĐ',
                'bullets' => array(
                    'Xây dựng mô hình tài chính dự báo ngân sách, đánh giá hiệu quả kinh doanh các công ty thành viên.',
                    'Phân tích tính khả thi tài chính của các dự án đầu tư công nghệ mới và thương vụ M&A chiến lược.',
                    'Lập báo cáo quản trị định kỳ gửi Ban Tổng Giám Đốc và Hội Đồng Quản Trị phục vụ điều hành.'
                )
            ),
            array(
                'title' => 'Kế Toán Quản Trị & Quản Lý Dòng Tiền Fintech',
                'company' => 'Ví Điện Tử MoMo',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/momo-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '22.000.000 VNĐ - 32.000.000 VNĐ',
                'bullets' => array(
                    'Theo dõi, phân tích dòng tiền thanh toán hàng ngày qua cổng điện tử và đối soát công nợ đối tác.',
                    'Xây dựng các kịch bản cân đối vốn lưu động và tối ưu hóa chi phí vận hành dịch vụ tài chính.',
                    'Phối hợp cùng bộ phận sản phẩm để tự động hóa quy trình kế toán trên hệ thống ERP doanh nghiệp.'
                )
            ),
            array(
                'title' => 'Kế Toán Trưởng Ban Quản Lý Dự Án Xây Dựng',
                'company' => 'Tập Đoàn Xây Dựng Coteccons',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/coteccons-logo.png',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '28.000.000 VNĐ - 40.000.000 VNĐ',
                'bullets' => array(
                    'Quản lý chi phí thi công, kiểm soát ngân sách vật tư và thanh quyết toán khối lượng nhà thầu phụ.',
                    'Kiểm tra chứng từ, hồ sơ giải ngân ngân hàng và theo dõi công nợ khách hàng cho các dự án lớn.',
                    'Lập báo cáo kết quả tài chính từng công trình và tư vấn giải pháp tiết giảm chi phí cho ban giám đốc.'
                )
            )
        )
    ),
    'nha-hang-thuc-pham' => array(
        'name' => 'Nhà hàng & Thực phẩm',
        'jobs' => array(
            array(
                'title' => 'Giám Đốc Vận Hành Chuỗi Nhà Hàng Ẩm Thực (F&B)',
                'company' => 'Golden Gate Restaurant Group',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/goldengate-logo.png',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '35.000.000 VNĐ - 50.000.000 VNĐ',
                'bullets' => array(
                    'Chịu trách nhiệm điều hành toàn diện hoạt động kinh doanh chuỗi nhà hàng lẩu nướng cao cấp.',
                    'Hoạch định chiến lược kinh doanh, tối ưu hóa doanh thu và kiểm soát chi phí thực phẩm (Food Cost).',
                    'Đào tạo và phát triển đội ngũ quản lý chi nhánh, nâng cao tiêu chuẩn phục vụ khách hàng chuẩn mực.'
                )
            ),
            array(
                'title' => 'Quản Lý Cửa Hàng Chuỗi F&B Khu Vực (Store Manager)',
                'company' => 'Golden Gate Restaurant Group',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/goldengate-logo.png',
                'location' => 'Hà Nội',
                'salary' => '18.000.000 VNĐ - 26.000.000 VNĐ',
                'bullets' => array(
                    'Giám sát vận hành hằng ngày tại cửa hàng, duy trì chất lượng đồ ăn và tiêu chuẩn vệ sinh nghiêm ngặt.',
                    'Quản lý lịch làm việc nhân sự, sắp xếp ca trực và đào tạo kỹ năng chăm sóc khách hàng cho nhân viên.',
                    'Xử lý phản hồi của khách hàng nhanh chóng, nâng cao điểm hài lòng và gia tăng lượng khách hàng thân thiết.'
                )
            ),
            array(
                'title' => 'Bếp Trưởng Điều Hành & Sáng Tạo Món Mới (Head Chef)',
                'company' => 'Chuỗi Nhà Hàng Golden Gate',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/goldengate-logo.png',
                'location' => 'Đà Nẵng',
                'salary' => '25.000.000 VNĐ - 35.000.000 VNĐ',
                'bullets' => array(
                    'Quản lý toàn bộ quy trình chế biến, bảo quản thực phẩm và kiểm soát chất lượng món ăn đồng nhất.',
                    'Nghiên cứu, phát triển thực đơn mới theo mùa phù hợp với khẩu vị đa dạng của thực khách.',
                    'Đào tạo tay nghề cho đội ngũ phụ bếp và giám sát việc tuân thủ tuyệt đối an toàn vệ sinh thực phẩm.'
                )
            ),
            array(
                'title' => 'Chuyên Viên Quản Lý Chất Lượng & Vệ Sinh ATTP Chuỗi',
                'company' => 'Golden Gate Restaurant Group',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/goldengate-logo.png',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '17.000.000 VNĐ - 24.000.000 VNĐ',
                'bullets' => array(
                    'Thực hiện kiểm tra định kỳ và đột xuất quy trình vệ sinh an toàn thực phẩm tại các nhà hàng chi nhánh.',
                    'Làm việc với các đơn vị kiểm nghiệm thực phẩm và cơ quan quản lý nhà nước về giấy phép ATTP.',
                    'Tổ chức tập huấn kiến thức an toàn thực phẩm và phòng cháy chữa cháy cho toàn bộ nhân sự khối nhà hàng.'
                )
            )
        )
    ),
    'quan-tri-nhan-su' => array(
        'name' => 'Quản trị nhân sự',
        'jobs' => array(
            array(
                'title' => 'Trưởng Phòng Tuyển Dụng & Thu Hút Nhân Tài (HR Manager)',
                'company' => 'Tập Đoàn Vingroup',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vingroup-logo.jpg',
                'location' => 'Hà Nội',
                'salary' => '35.000.000 VNĐ - 50.000.000 VNĐ',
                'bullets' => array(
                    'Hoạch định chiến lược tuyển dụng nhân sự cấp cao và thu hút nhân tài cho các khối kinh doanh trọng yếu.',
                    'Xây dựng và phát triển thương hiệu nhà tuyển dụng uy tín thông qua các ngày hội việc làm và mạng xã hội.',
                    'Tối ưu hóa quy trình phỏng vấn, rút ngắn thời gian tuyển dụng và nâng cao tỷ lệ ứng viên nhận việc.'
                )
            ),
            array(
                'title' => 'Senior HR Business Partner (HRBP)',
                'company' => 'Công Ty TNHH Shopee Việt Nam',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/shopee-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '30.000.000 VNĐ - 45.000.000 VNĐ',
                'bullets' => array(
                    'Đồng hành cùng ban lãnh đạo khối Thương mại điện tử để tư vấn giải pháp nhân sự và cơ cấu tổ chức.',
                    'Quản trị hiệu suất làm việc (OKRs/KPIs), tổ chức đánh giá định kỳ và xây dựng lộ trình phát triển nhân sự.',
                    'Giải quyết các quan hệ lao động, thúc đẩy sự gắn kết nhân viên và lan tỏa văn hóa doanh nghiệp sáng tạo.'
                )
            ),
            array(
                'title' => 'Chuyên Viên Đào Tạo & Phát Triển Nhân Lực (L&D Lead)',
                'company' => 'FPT Software',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/fpt-logo.jpg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '20.000.000 VNĐ - 30.000.000 VNĐ',
                'bullets' => array(
                    'Khảo sát nhu cầu đào tạo chuyên môn công nghệ và kỹ năng mềm cho đội ngũ lập trình viên toàn công ty.',
                    'Thiết kế bài giảng e-learning, phối hợp với các chuyên gia công nghệ để triển khai khóa học thực tế.',
                    'Đánh giá hiệu quả sau đào tạo, đo lường ROI và cấp chứng chỉ nghề nghiệp theo tiêu chuẩn toàn cầu.'
                )
            ),
            array(
                'title' => 'Chuyên Viên Cao Cấp Tiền Lương & Phúc Lợi (C&B Specialist)',
                'company' => 'VNG Corporation',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vng-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '22.000.000 VNĐ - 32.000.000 VNĐ',
                'bullets' => array(
                    'Thực hiện tính toán bảng lương, thưởng hiệu quả, bảo hiểm xã hội và quyết toán thuế thu nhập cá nhân chuẩn xác.',
                    'Khảo sát thị trường lương thưởng Mercer, đề xuất gói đãi ngộ cạnh tranh để giữ chân nhân sự xuất sắc.',
                    'Quản lý hệ thống phúc lợi toàn diện gồm bảo hiểm sức khỏe quốc tế, gói khám định kỳ và du lịch nghỉ dưỡng.'
                )
            )
        )
    ),
    'web-and-marketing' => array(
        'name' => 'Thiết kế Web & Marketing',
        'jobs' => array(
            array(
                'title' => 'Chuyên Viên Digital Marketing & E-Commerce Lead',
                'company' => 'Công Ty TNHH Shopee Việt Nam',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/shopee-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '25.000.000 VNĐ - 38.000.000 VNĐ',
                'bullets' => array(
                    'Lên kế hoạch và thực thi các chiến dịch Performance Marketing trên Facebook Ads, Google Ads và TikTok Ads.',
                    'Tối ưu hóa tỷ lệ chuyển đổi đơn hàng (CR), chi phí thu hút khách hàng mới (CAC) và giá trị vòng đời khách hàng.',
                    'Phối hợp với đội ngũ sáng tạo để sản xuất nội dung viral, video ngắn thu hút hàng triệu lượt xem.'
                )
            ),
            array(
                'title' => 'Senior UI/UX Product Designer',
                'company' => 'VNG Corporation',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vng-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '28.000.000 VNĐ - 42.000.000 VNĐ',
                'bullets' => array(
                    'Nghiên cứu hành vi người dùng, xây dựng User Flow, Wireframe và Prototype chi tiết trên Figma.',
                    'Xây dựng và hoàn thiện Design System đồng bộ cho hệ sinh thái sản phẩm công nghệ của tập đoàn.',
                    'Kiểm thử khả năng sử dụng (Usability Testing), phân tích dữ liệu nhiệt để liên tục cải tiến giao diện.'
                )
            ),
            array(
                'title' => 'Growth Marketing Lead & Chiến Lược Tăng Trưởng',
                'company' => 'Ví Điện Tử MoMo',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/momo-logo.svg',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '30.000.000 VNĐ - 45.000.000 VNĐ',
                'bullets' => array(
                    'Phân tích phễu chuyển đổi người dùng, tìm kiếm cơ hội tăng trưởng đột phá cho các sản phẩm tài chính.',
                    'Triển khai các chương trình Gamification, ưu đãi đối tác liên kết để kích thích giao dịch thanh toán.',
                    'Ứng dụng A/B Testing liên tục trên ứng dụng để tối ưu trải nghiệm và tỷ lệ giữ chân khách hàng (Retention).'
                )
            ),
            array(
                'title' => 'Trưởng Nhóm Content Marketing & Truyền Thông Thương Hiệu',
                'company' => 'FPT Online',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/fpt-logo.jpg',
                'location' => 'Hà Nội',
                'salary' => '20.000.000 VNĐ - 30.000.000 VNĐ',
                'bullets' => array(
                    'Định hình thông điệp truyền thông, xây dựng chiến lược nội dung đa kênh trên báo điện tử và mạng xã hội.',
                    'Quản lý sản xuất bài viết chuyên sâu, phỏng vấn chuyên gia và các tuyến bài phân tích công nghệ chất lượng cao.',
                    'Đo lường hiệu quả tiếp cận thương hiệu và tối ưu thứ hạng từ khóa trên công cụ tìm kiếm Google.'
                )
            )
        )
    ),
    'xay-dung-co-so-ha-tang' => array(
        'name' => 'Xây dựng & Cơ sở hạ tầng',
        'jobs' => array(
            array(
                'title' => 'Kỹ Sư Giám Sát Công Trình Xây Dựng Cấp 1',
                'company' => 'Công Ty Cổ Phần Xây Dựng Coteccons',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/coteccons-logo.png',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '25.000.000 VNĐ - 36.000.000 VNĐ',
                'bullets' => array(
                    'Trực tiếp giám sát chất lượng thi công phần thô và hoàn thiện tại các đại dự án cao ốc và khu đô thị.',
                    'Kiểm soát tiến độ thi công, nghiệm thu từng hạng mục công việc với chủ đầu tư và tư vấn giám sát.',
                    'Đảm bảo tuân thủ nghiêm ngặt các tiêu chuẩn an toàn lao động và vệ sinh môi trường trên công trường.'
                )
            ),
            array(
                'title' => 'Kỹ Sư Kết Cấu Công Trình Cao Tầng (BIM Specialist)',
                'company' => 'Tập Đoàn Xây Dựng Coteccons',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/coteccons-logo.png',
                'location' => 'Hà Nội',
                'salary' => '26.000.000 VNĐ - 38.000.000 VNĐ',
                'bullets' => array(
                    'Tính toán và thiết kế kết cấu bê tông cốt thép, kết cấu thép cho các công trình cao tầng phức tạp.',
                    'Ứng dụng mô hình thông tin công trình (BIM Revit) để phát hiện xung đột và tối ưu hóa giải pháp thi công.',
                    'Phối hợp với bộ phận kiến trúc và cơ điện MEP để đảm bảo tính đồng bộ của hồ sơ thiết kế kỹ thuật.'
                )
            ),
            array(
                'title' => 'Chỉ Huy Trưởng Công Trường Dự Án Đô Thị Lớn',
                'company' => 'Tập Đoàn Vingroup (Vinhomes)',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vingroup-logo.jpg',
                'location' => 'Hà Nội',
                'salary' => '45.000.000 VNĐ - 60.000.000 VNĐ',
                'bullets' => array(
                    'Chịu trách nhiệm cao nhất về an toàn, tiến độ, chất lượng và hiệu quả chi phí của toàn bộ công trường.',
                    'Điều phối các nhà thầu phụ, nhà cung ứng vật tư và làm việc chặt chẽ với cơ quan chức năng địa phương.',
                    'Quản lý nguồn lực nhân sự, máy móc thiết bị và ứng phó kịp thời với các rủi ro phát sinh trong thi công.'
                )
            ),
            array(
                'title' => 'Kỹ Sư Quản Lý Dự Án Cơ Điện Công Trình (MEP Engineer)',
                'company' => 'Công Ty Cổ Phần Xây Dựng Coteccons',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/coteccons-logo.png',
                'location' => 'Đà Nẵng',
                'salary' => '22.000.000 VNĐ - 32.000.000 VNĐ',
                'bullets' => array(
                    'Giám sát thi công và lắp đặt hệ thống điện, cấp thoát nước, thông gió điều hòa (HVAC) và PCCC.',
                    'Tham gia công tác chạy thử liên động (T&C), nghiệm thu bàn giao hệ thống kỹ thuật đưa vào sử dụng.',
                    'Kiểm soát khối lượng thanh toán cơ điện và xử lý các vấn đề kỹ thuật phát sinh tại hiện trường.'
                )
            )
        )
    ),
    'y-te-suc-khoe' => array(
        'name' => 'Y tế & Chăm sóc sức khỏe',
        'jobs' => array(
            array(
                'title' => 'Bác Sĩ Trưởng Khoa Nội Tổng Hợp',
                'company' => 'Bệnh Viện Đa Khoa Quốc Tế Vinmec',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vinmec-logo.png',
                'location' => 'Hà Nội',
                'salary' => '40.000.000 VNĐ - 60.000.000 VNĐ',
                'bullets' => array(
                    'Trực tiếp thăm khám, chẩn đoán và hội chẩn các ca bệnh lý nội khoa phức tạp theo phác đồ quốc tế JCI.',
                    'Điều hành hoạt động chuyên môn của khoa, đào tạo và hướng dẫn các bác sĩ trẻ, điều dưỡng viên.',
                    'Tham gia nghiên cứu khoa học lâm sàng và hợp tác y khoa với các bệnh viện hàng đầu thế giới.'
                )
            ),
            array(
                'title' => 'Bác Sĩ Chuyên Khoa Tim Mạch Can Thiệp',
                'company' => 'Bệnh Viện Đa Khoa Quốc Tế Vinmec',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vinmec-logo.png',
                'location' => 'TP. Hồ Chí Minh',
                'salary' => '45.000.000 VNĐ - 70.000.000 VNĐ',
                'bullets' => array(
                    'Thực hiện các kỹ thuật can thiệp tim mạch chuyên sâu như đặt stent mạch vành, nong mạch máu.',
                    'Cấp cứu và điều trị tích cực cho các bệnh nhân nhồi máu cơ tim, suy tim cấp và rối loạn nhịp tim nguy hiểm.',
                    'Tư vấn chế độ dinh dưỡng, lối sống và quản lý bệnh lý tim mạch mạn tính cho bệnh nhân sau xuất viện.'
                )
            ),
            array(
                'title' => 'Điều Dưỡng Trưởng Khối Chăm Sóc Tích Cực (ICU Lead)',
                'company' => 'Bệnh Viện Đa Khoa Quốc Tế Vinmec',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vinmec-logo.png',
                'location' => 'Đà Nẵng',
                'salary' => '20.000.000 VNĐ - 28.000.000 VNĐ',
                'bullets' => array(
                    'Quản lý và điều phối hoạt động chăm sóc bệnh nhân nặng tại khoa hồi sức cấp cứu và tích cực.',
                    'Giám sát việc tuân thủ quy trình kiểm soát nhiễm khuẩn, dùng thuốc đúng chỉ định và y đức nghề nghiệp.',
                    'Đào tạo kỹ năng hồi sức cấp cứu cơ bản và nâng cao cho đội ngũ điều dưỡng viên trong bệnh viện.'
                )
            ),
            array(
                'title' => 'Dược Sĩ Lâm Sàng & Quản Lý Dược Phẩm Bệnh Viện',
                'company' => 'Bệnh Viện Đa Khoa Quốc Tế Vinmec',
                'logo' => 'http://wordpress.local/wp-content/uploads/2026/10/vinmec-logo.png',
                'location' => 'Hà Nội',
                'salary' => '22.000.000 VNĐ - 32.000.000 VNĐ',
                'bullets' => array(
                    'Tham gia hội chẩn cùng bác sĩ điều trị để tư vấn sử dụng thuốc an toàn, hợp lý và đạt hiệu quả tối ưu.',
                    'Theo dõi phản ứng có hại của thuốc (ADR), giám sát việc tương tác thuốc và quản lý kho dược đạt chuẩn GSP.',
                    'Cung cấp thông tin thuốc chính xác cho nhân viên y tế và hướng dẫn bệnh nhân sử dụng thuốc đúng cách.'
                )
            )
        )
    )
);

// Delete all existing job_listing posts to remove duplicates and start completely clean
$existing_jobs = get_posts( array(
    'post_type'      => 'job_listing',
    'post_status'    => 'any',
    'posts_per_page' => -1,
) );
foreach ( $existing_jobs as $ej ) {
    wp_delete_post( $ej->ID, true );
}

$fulltime_term = get_term_by( 'slug', 'toan-thoi-gian', 'job_listing_type' );
if ( ! $fulltime_term ) {
    $inserted_type = wp_insert_term( 'Toàn thời gian', 'job_listing_type', array( 'slug' => 'toan-thoi-gian' ) );
    $fulltime_id   = is_array( $inserted_type ) ? $inserted_type['term_id'] : 0;
} else {
    $fulltime_id = $fulltime_term->term_id;
    wp_update_term( $fulltime_id, 'job_listing_type', array( 'name' => 'Toàn thời gian' ) );
}

$count_created = 0;

foreach ( $categories_data as $cat_slug => $cat_info ) {
    $term = get_term_by( 'slug', $cat_slug, 'job_listing_category' );
    if ( ! $term ) {
        $inserted = wp_insert_term( $cat_info['name'], 'job_listing_category', array( 'slug' => $cat_slug ) );
        $cat_id = is_array( $inserted ) ? $inserted['term_id'] : 0;
    } else {
        $cat_id = $term->term_id;
        wp_update_term( $cat_id, 'job_listing_category', array( 'name' => $cat_info['name'] ) );
    }

    foreach ( $cat_info['jobs'] as $job_data ) {
        $content = '<ul>';
        foreach ( $job_data['bullets'] as $bullet ) {
            $content .= '<li>' . esc_html( $bullet ) . '</li>';
        }
        $content .= '</ul>';

        $post_id = wp_insert_post( array(
            'post_title'   => $job_data['title'],
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_type'    => 'job_listing',
            'post_author'  => 1,
            'post_date'    => '2026-10-14 08:00:00',
        ) );

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            if ( $cat_id ) {
                wp_set_post_terms( $post_id, array( $cat_id ), 'job_listing_category' );
            }
            if ( $fulltime_id ) {
                wp_set_post_terms( $post_id, array( $fulltime_id ), 'job_listing_type' );
            }

            update_post_meta( $post_id, '_company_name', $job_data['company'] );
            update_post_meta( $post_id, '_company_logo', $job_data['logo'] );
            update_post_meta( $post_id, '_job_location', $job_data['location'] );
            update_post_meta( $post_id, '_job_salary', $job_data['salary'] );
            update_post_meta( $post_id, '_filled', 0 );
            update_post_meta( $post_id, '_featured', 0 );
            update_post_meta( $post_id, '_job_expires', '2030-12-31' );
            update_post_meta( $post_id, '_application', 'recruitment@' . sanitize_title( $job_data['company'] ) . '.vn' );

            $count_created++;
        }
    }
}

echo "SUCCESS: Created exactly {$count_created} distinct jobs (4 jobs per each of 8 categories)!\n";
