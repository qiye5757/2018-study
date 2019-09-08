## git学习

### 最简单的用法

1. git init （创建新的项目）
2. git add file	（添加文件到本地库）
3. git commit -m "描述"  （提交到本地库）
4. git remote add origin 你的git服务器地址  （添加远程库）
5. git push -u origin master（推送到远程库）
6. git clone + 地址及项目 （下载远程库到本地）
7. git fetch origin master （下载最新的）

### git 常用操作之保存进度和恢复进度

+ 保存进度: `git stash`
+ 保存进度并添加信息 `git stash save "message"`
+ 查看进度列表：`git stash list`
+ 恢复进度  `git stash pop` 或者 `git satsh apply`(pop会删除当前的进度)

    + `git stash pop` 恢复最新的（全部恢复到工作区）
    + `git stash pop --index` 恢复到最新的（可以将暂存区的文件恢复到暂存区）
    + `git stash pop stash的id` 恢复指定stash
    
+ 移除进度 `git stash drop` （不加stash_id则默认移除最新的）
+ 移除所有的进度 `git stash clear`
+ 取消进度 `git stash show -p | git apply -R`
       
        