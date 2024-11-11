1. Clone mã nguồn về máy:

   ```bash
   git clone https://github.com/tophvn/HPL.git

2. Thêm project vào www của laragon
3. add database vào phpmyadmin
   
   laragon: root (ko mật khẩu)
   
   Download Laragon: https://laragon.org/download/
   
commit(còn thiếu các hành động search, lọc, order, history, checkout,...)

### Cách push code lên repo: 

git init

git add .

git remote add origin https://github.com/tophvn/HPL.git

git pull origin main --allow-unrelated-histories   (kéo thay đổi về)

git commit -m " "

git push origin main

git branch -m master main

git push origin main --force (xóa toàn bộ và push lại) k nên 

git remote remove origin


<<<<<<< HEAD
git remote set-url origin https://github.com/tophvn/HPL.git
=======
git remote set-url origin https://github.com/tophvn/HPL.git
>>>>>>> origin/main
