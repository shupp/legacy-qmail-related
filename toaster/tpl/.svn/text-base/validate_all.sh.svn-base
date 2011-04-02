#!/bin/bash

for i in `ls *.xml | egrep -v '(wrapper|outline|renderers|versions)' ` ; do
    echo $i
    php validate.php wrapper.xml $i
done
