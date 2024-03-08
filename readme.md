<h1 class="code-line" data-line-start=0 data-line-end=1 ><a id="21st_Shop_0"></a>21st Shop</h1>
<h2 class="code-line" data-line-start=1 data-line-end=2 ><a id="Deploying_wordpress_using_nginx_on_ubuntu_1"></a>Deploying wordpress using nginx on ubuntu</h2>
<p class="has-line-data" data-line-start="4" data-line-end="5">Please note that these instructions assume a basic Ubuntu server setup and may need adjustments based on your specific server environment. To get started:</p>
<ul>
<li class="has-line-data" data-line-start="6" data-line-end="7">ssh into terminal</li>
<li class="has-line-data" data-line-start="7" data-line-end="9">✨Work your Magic ✨</li>
</ul>
<h2 class="code-line" data-line-start=9 data-line-end=10 ><a id="Features_9"></a>Features</h2>
<ul>
<li class="has-line-data" data-line-start="11" data-line-end="12">Installing wordpress set dependencies of php, mysql and nginx, maybe more we’ll seeing later during this documentation.</li>
</ul>
<h2 class="code-line" data-line-start=14 data-line-end=15 ><a id="Step_1_Update_and_Upgrade_System_Packages_14"></a>Step 1: Update and Upgrade System Packages</h2>
<pre><code class="has-line-data" data-line-start="17" data-line-end="20" class="language-sh">sudo apt update
sudo apt upgrade
</code></pre>
<h2 class="code-line" data-line-start=21 data-line-end=22 ><a id="Step_2_Install_Nginx_21"></a>Step 2: Install Nginx</h2>
<pre><code class="has-line-data" data-line-start="24" data-line-end="26" class="language-sh">sudo apt install nginx
</code></pre>
<h2 class="code-line" data-line-start=27 data-line-end=28 ><a id="Step_3_Install_MySQL_server_27"></a>Step 3: Install MySQL server</h2>
<pre><code class="has-line-data" data-line-start="29" data-line-end="31" class="language-sh">sudo mysql_secure_installation
</code></pre>
<h2 class="code-line" data-line-start=32 data-line-end=33 ><a id="Step_4_Install_PHP_and_Required_Extensions_32"></a>Step 4: Install PHP and Required Extensions</h2>
<pre><code class="has-line-data" data-line-start="34" data-line-end="36" class="language-sh">sudo apt install php-fpm php-mysql php-cli php-common php-curl php-gd php-mbstring php-xml
</code></pre>
<h2 class="code-line" data-line-start=36 data-line-end=37 ><a id="Step_5_Create_MySQL_Database_and_User_for_WordPress_36"></a>Step 5: Create MySQL Database and User for WordPress</h2>
<p class="has-line-data" data-line-start="37" data-line-end="38">Access the MySQL shell:</p>
<pre><code class="has-line-data" data-line-start="39" data-line-end="41" class="language-sh">mysql -u root
</code></pre>
<p class="has-line-data" data-line-start="41" data-line-end="42">…in my case I didn’t have the root password so i proceeded without one.</p>
<p class="has-line-data" data-line-start="43" data-line-end="44">To create database and grant privileges;</p>
<pre><code class="has-line-data" data-line-start="46" data-line-end="52" class="language-sh">CREATE DATABASE wordpress;
CREATE USER <span class="hljs-string">'wordpressuser'</span>@<span class="hljs-string">'127.0.0.1'</span> IDENTIFIED BY <span class="hljs-string">'your_password'</span>;
GRANT ALL PRIVILEGES ON wordpress.* TO <span class="hljs-string">'wordpressuser'</span>@<span class="hljs-string">'127.0.0.1'</span>;
FLUSH PRIVILEGES;
EXIT;
</code></pre>
<h2 class="code-line" data-line-start=53 data-line-end=54 ><a id="Step_6_Configure_and_Install_Wordpress_53"></a>Step 6: Configure and Install Wordpress</h2>
<p class="has-line-data" data-line-start="54" data-line-end="55">Copy the sample configuration file:</p>
<pre><code class="has-line-data" data-line-start="56" data-line-end="65" class="language-sh"><span class="hljs-built_in">cd</span> /var/www/html
sudo wget https://wordpress.org/latest.tar.gz
sudo tar -xzvf latest.tar.gz
sudo mv wordpress/* .
sudo rm -rf wordpress latest.tar.gz
sudo chown -R www-data:www-data /var/www/html
cp wp-config-sample.php wp-config.php
nano wp-config.php
</code></pre>
<p class="has-line-data" data-line-start="66" data-line-end="67">You may edit wp-config.php file by:</p>
<pre><code class="has-line-data" data-line-start="68" data-line-end="70" class="language-sh">sudo nano wp-config.php
</code></pre>
<pre><code class="has-line-data" data-line-start="72" data-line-end="77" class="language-sh">define(<span class="hljs-string">'DB_NAME'</span>, <span class="hljs-string">'wordpress'</span>);
define(<span class="hljs-string">'DB_USER'</span>, <span class="hljs-string">'wordpress_user'</span>);
define(<span class="hljs-string">'DB_PASSWORD'</span>, <span class="hljs-string">'your_password'</span>);
define(<span class="hljs-string">'DB_HOST'</span>, <span class="hljs-string">'localhost'</span>);
</code></pre>
<h2 class="code-line" data-line-start=78 data-line-end=79 ><a id="Step_7_Configure_Nginx_for_Wordpress_78"></a>Step 7: Configure Nginx for Wordpress</h2>
<pre><code class="has-line-data" data-line-start="81" data-line-end="83" class="language-sh">sudo nano /etc/nginx/sites-available/default
</code></pre>
<p class="has-line-data" data-line-start="84" data-line-end="85">Update the server block:</p>
<pre><code class="has-line-data" data-line-start="86" data-line-end="113" class="language-sh">server {
    listen <span class="hljs-number">80</span>;
    server_name <span class="hljs-number">127.0</span>.<span class="hljs-number">0.1</span>;

    root /var/www/html;
    index index.php index.html index.htm; //make sure you have index.php to denote wordpress setup page

    location / {
        try_files <span class="hljs-variable">$uri</span> <span class="hljs-variable">$uri</span>/ /index.php?<span class="hljs-variable">$args</span>;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.<span class="hljs-number">1</span>-fpm.sock;
        fastcgi_param SCRIPT_FILENAME <span class="hljs-variable">$document_root</span><span class="hljs-variable">$fastcgi_script_name</span>;
        include fastcgi_params;
    }
    
    location = /favicon.icon { <span class="hljs-built_in">log</span>_not_found off; }
        location = /robots.txt { <span class="hljs-built_in">log</span>_not_found off; access_<span class="hljs-built_in">log</span> off; allow all; }
        location ~* \.(css|gif|ico|jpeg|jpg|js|png)$ { <span class="hljs-built_in">log</span>_not_found off; }

    location ~ /\.ht {
        deny all;
    }
}
</code></pre>
<p class="has-line-data" data-line-start="113" data-line-end="114">Restart Nginx:</p>
<pre><code class="has-line-data" data-line-start="115" data-line-end="117" class="language-sh">sudo systemctl restart nginx
</code></pre>
<h2 class="code-line" data-line-start=118 data-line-end=119 ><a id="Step_8_Complete_wordpress_installation_118"></a>Step 8: Complete wordpress installation</h2>
<p class="has-line-data" data-line-start="119" data-line-end="120">You may now complete wordpress installation by visiting your domain or 127.0.0.1 localhost. If you’re not received with a wordpress welcome, you may for installation by accessing wp-config.php</p>
