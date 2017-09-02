#!/usr/bin/env python
# encoding: utf-8
from fabric.api import local,run,cd,env,put,roles
env.user = 'root'
env.roledefs = {
    'prod': ['111.230.142.155'],
    'test': ['111.230.140.74'],
}
remote_project_dir = '/data/wwwroot/bahe-api'

def clean():
    print('清除本地项目tar.gz文件')
    local('rm -f *.tar.gz')
def pack():
    print('打包本地项目文件')
    local_project_dir = '/Users/cloud/laravel_project/bahe-api'
    with cd(local_project_dir):
        local('tar czf project.tar.gz --exclude=.* --exclude=fabfile.* --exclude=*.tar.gz ./*')
def deploy(remote_project_dir):
    print('发布项目压缩包')
    put('project.tar.gz', remote_project_dir)
def unpack():
    print('远程解压文件')
    with cd(remote_project_dir):
        run('tar xzf project.tar.gz') 
        run('rm -f project.tar.gz')
        run('chown -R www:www ./*')
        run('chmod -R 777 storage')
@roles('prod')
def prod():
    clean()
    pack()
    deploy(remote_project_dir)
    unpack()
@roles('test')
def test():
    clean()
    pack()
    deploy(remote_project_dir)
    unpack()
