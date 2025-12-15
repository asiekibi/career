<?xml version="1.0" encoding="UTF-8"?>
<jmeterTestPlan version="1.2" properties="5.0" jmeter="5.6.2">
  <hashTree>

    <!-- ================= TEST PLAN ================= -->
    <TestPlan guiclass="TestPlanGui" testclass="TestPlan" testname="Laravel Exam System - 100 Users Load Test" enabled="true">
      <stringProp name="TestPlan.comments">100 Concurrent Users Exam Load Test</stringProp>
      <boolProp name="TestPlan.functional_mode">false</boolProp>
      <boolProp name="TestPlan.tearDown_on_shutdown">true</boolProp>
      <boolProp name="TestPlan.serialize_threadgroups">false</boolProp>
    </TestPlan>

    <hashTree>

      <!-- ================= THREAD GROUP ================= -->
      <ThreadGroup guiclass="ThreadGroupGui" testclass="ThreadGroup" testname="100 Concurrent Users" enabled="true">
        <stringProp name="ThreadGroup.on_sample_error">continue</stringProp>

        <elementProp name="ThreadGroup.main_controller" elementType="LoopController">
          <boolProp name="LoopController.continue_forever">false</boolProp>
          <intProp name="LoopController.loops">1</intProp>
        </elementProp>

        <!-- 100 USER -->
        <stringProp name="ThreadGroup.num_threads">100</stringProp>

        <!-- 2 DAKİKADA KADEMELİ -->
        <stringProp name="ThreadGroup.ramp_time">120</stringProp>
      </ThreadGroup>

      <hashTree>

        <!-- ================= COOKIE MANAGER ================= -->
        <CookieManager guiclass="CookiePanel" testclass="CookieManager" testname="HTTP Cookie Manager" enabled="true">
          <boolProp name="CookieManager.clearEachIteration">false</boolProp>
          <boolProp name="CookieManager.controlledByThreadGroup">true</boolProp>
        </CookieManager>
        <hashTree/>

        <!-- ================= HTTP DEFAULTS ================= -->
        <ConfigTestElement guiclass="HttpDefaultsGui" testclass="ConfigTestElement" testname="HTTP Defaults" enabled="true">
          <stringProp name="HTTPSampler.domain">asieduportal.com</stringProp>
          <stringProp name="HTTPSampler.protocol">https</stringProp>
          <stringProp name="HTTPSampler.connect_timeout">15000</stringProp>
          <stringProp name="HTTPSampler.response_timeout">60000</stringProp>
        </ConfigTestElement>
        <hashTree/>

        <!-- ================= DEFAULT HEADERS ================= -->
        <HeaderManager guiclass="HeaderPanel" testclass="HeaderManager" testname="Default Headers" enabled="true">
          <collectionProp name="HeaderManager.headers">
            <elementProp name="" elementType="Header">
              <stringProp name="Header.name">User-Agent</stringProp>
              <stringProp name="Header.value">Mozilla/5.0</stringProp>
            </elementProp>
            <elementProp name="" elementType="Header">
              <stringProp name="Header.name">Accept</stringProp>
              <stringProp name="Header.value">application/json,text/html</stringProp>
            </elementProp>
          </collectionProp>
        </HeaderManager>
        <hashTree/>

        <!-- ================= 1. EXAM DETAIL PAGE ================= -->
        <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="1. Exam Detail Page" enabled="true">
          <stringProp name="HTTPSampler.method">GET</stringProp>
          <stringProp name="HTTPSampler.path">/exam/test-sinav-2024-2025-12-15/detail</stringProp>
          <boolProp name="HTTPSampler.follow_redirects">true</boolProp>
        </HTTPSamplerProxy>

        <hashTree>
          <!-- CSRF TOKEN -->
          <RegexExtractor guiclass="RegexExtractorGui" testclass="RegexExtractor" testname="Extract CSRF Token" enabled="true">
            <stringProp name="RegexExtractor.refname">CSRF_TOKEN</stringProp>
            <stringProp name="RegexExtractor.regex">&lt;meta name=&quot;csrf-token&quot; content=&quot;(.+?)&quot;</stringProp>
            <stringProp name="RegexExtractor.template">$1$</stringProp>
            <stringProp name="RegexExtractor.match_number">1</stringProp>
            <stringProp name="RegexExtractor.default">NOT_FOUND</stringProp>
          </RegexExtractor>
          <hashTree/>
        </hashTree>

        <!-- ================= 2. PREPARE EXAM START ================= -->
        <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="2. Prepare Exam Start" enabled="true">
          <stringProp name="HTTPSampler.method">POST</stringProp>
          <stringProp name="HTTPSampler.path">/exam/test-sinav-2024-2025-12-15/prepare-start</stringProp>
          <stringProp name="HTTPSampler.implementation">HttpClient4</stringProp>

          <elementProp name="HTTPsampler.Arguments" elementType="Arguments">
            <collectionProp name="Arguments.arguments">
              <elementProp name="" elementType="HTTPArgument">
                <stringProp name="Argument.value">
                  {"name":"User ${__threadNum}","surname":"Test","email":"user${__threadNum}@test.com","phone":"555000${__threadNum}"}
                </stringProp>
                <stringProp name="Argument.metadata">=</stringProp>
              </elementProp>
            </collectionProp>
          </elementProp>

          <collectionProp name="HTTPsampler.headers">
            <elementProp name="" elementType="Header">
              <stringProp name="Header.name">Content-Type</stringProp>
              <stringProp name="Header.value">application/json</stringProp>
            </elementProp>
            <elementProp name="" elementType="Header">
              <stringProp name="Header.name">X-CSRF-TOKEN</stringProp>
              <stringProp name="Header.value">${CSRF_TOKEN}</stringProp>
            </elementProp>
          </collectionProp>
        </HTTPSamplerProxy>

        <hashTree/>

        <!-- ================= 3. TAKE EXAM PAGE ================= -->
        <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="3. Take Exam Page" enabled="true">
          <stringProp name="HTTPSampler.method">GET</stringProp>
          <stringProp name="HTTPSampler.path">/exam/test-sinav-2024-2025-12-15</stringProp>
          <boolProp name="HTTPSampler.follow_redirects">true</boolProp>
        </HTTPSamplerProxy>

        <hashTree/>

        <!-- ================= RESULTS ================= -->
        <ResultCollector guiclass="SummaryReport" testclass="ResultCollector" testname="Summary Report" enabled="true"/>
        <hashTree/>

      </hashTree>
    </hashTree>
  </hashTree>
</jmeterTestPlan>
