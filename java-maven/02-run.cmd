@echo off

set project_name=core
set version=1.0-SNAPSHOT

set package=target/%project_name%-%version%-shaded.jar

"%JAVA_HOME%\bin\java" -jar %package%
