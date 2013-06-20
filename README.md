# SquidViz README.md

Oh hi!  I didn't see you there.  How are you?  Good!  SquidViz, yeah?

So SquidViz is a web-based visualizer for Ceph clusters, designed to help developers and new users understand how Ceph works.  It is based on D3.js, weaving together code that was originally part of three of D3's examples.

There's a server-side piece that grabs info from Ceph (using "ceph osd tree" and "ceph pg dump", both with --format=json) and rebuilds the JSON so that it's in the hierarchical form that D3 wants.  Then, through a ridiculous collection of iframes and javascript hacks, it comes alive with updates on your cluster health.

# What SquidViz it is?

SquidViz is a great way for visual learners to understand how Ceph reacts to changing Ceph clusters.  When OSDs are down, you can see how Ceph degrades PGs accordingly.  When new pools are created, you can see how the cluster allocates PGs.  When moving data in and out of the cluster, you can see a running iops sparkline.

The Physical tree does not update automatically, so there's a refresh button.  The Logical tree *does* update automatically; you will soon realize, once you get to know it, that this is a feature that you will want to turn off from time to time.  That's what the checkbox does.

There's a hidden terminal window (hint: click on the red "squidviz" in the header) and a hidden PG state legend (hint: click on "LOGICAL").  These are collapsed by default to keep things tidy, as SquidViz was originally built for demo purposes.

# What SquidViz it is not?

This was built as a demonstration and learning tool.  As such, there are a few things that SquidViz particularly is *not*.

SquidViz is not a full-featured Ceph admininstration or monitoring system.  A real management tool would scale beyond a dozen or so OSDs, while this one will not.  A real management tool would also dynamically update, while this one relies on refreshing iframes.  In short, anyone doing real work with Ceph should outgrow this almost immediately.

SquidViz is not part of Ceph.

SquidViz is not an Inktank product.

# Installation

First, make sure that your machine has the right ceph.conf and client keyring.

1. Install Apache and PHP.

~ apt-get install apache2 libapache2-mod-php5
~ a2enmod php5

2. Clone the squidviz repo into your document root (often /var/www).

~ mv squidviz/ /var/www

3. Install shellinabox.

If you're on Ubuntu 12.10:

~ apt-get install shellinabox

If you're on Ubuntu 12.04, feel free to use the RPM I've provided for your convenience.

~ dpkg install shellinabox-foo.deb

If not, you can build from source!

4. Configure Apache to proxy requests to shellinabox.

~ <IfModule mod_proxy.c>
~   <Location /shell>
~     ProxyPass http://127.0.0.1:4200
~   </Location>
~ </IfModule>

5. Configure shellinabox to use the custom CSS for maximum Ceph design system compliance by adding this line to /etc/default/shellinabox:

~ SHELLINABOX_ARGS="--no-beep --localhost-only --service=/:SSH -t --static-file=styles.css:/var/www/squidviz/shell.css"
