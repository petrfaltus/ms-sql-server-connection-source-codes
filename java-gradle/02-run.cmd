@echo off

set project_name=core
set version=1.0-SNAPSHOT

set package=build/libs/%project_name%-%version%.jar

"%JAVA_HOME%\bin\java" -jar %package%
