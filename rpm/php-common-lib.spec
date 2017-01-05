Summary:   Used For Update Server Status
Name:      php-common-lib
Version:   %{_version}
Release:   %(echo $RELEASE)
Source:    / 
License:   GPL
Packager:  dongjiang.dongj@alibaba-inc.com
Group:     engine test team
URL:       http://gitlab.alibaba-inc.com/dongjiang.dongj/basa.git
BuildArch: noarch
Requires:  php-common = 5.3.8 
Requires:  php-xml = 5.3.8
Requires:  php-cli = 5.3.8

%description
A Testting LIB In Php.
%{_svn_path}
%{_svn_revision}

%define usrpath /usr/local/lib64/basa

%build

%install
mkdir -p .%{usrpath}
cp -rf $OLDPWD/../src/* .%{usrpath}/

%files
%defattr(-, root, root)
%attr(755, root, root) %{usrpath}/*

%post

%pre
echo "change safe_mode from On to Off"
sed -i 's/^safe_mode = On/safe_mode = Off/' /etc/php.ini
sed -i 's/^display_errors = Off/display_errors = On/' /etc/php.ini
sed -i 's/^error_log =.*/error_log = \/tmp\/php_error/' /etc/php.ini
echo "change completely"

%preun
# $1=0, uninstall
# $1=1, upgrade
if [ "$1" == "0" ]
then
    echo "uninstall php-test-common rpm package!"
fi

if [ "$1" == "1" ]
then
    echo "upgrade php-test-common rpm package!"
fi

%postun
