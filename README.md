# HUST OJ bs4 template
HUSTOJ에서 사용하는 template 입니다.

현재 sungil-oj 에서 사용중에 있습니다.

사이트 : https://code.juwon.info

이 템플릿을 사용할 경우 bs4admin 디렉토리의 관리자 파일로 연결됩니다.

oj-header.php에서 <b><pre>$OJ_ADMIN = "bs4admin"</pre></b>을 수정하시면 됩니다. (기본 디렉토리는 admin)

템플릿 navbar의 기본 색상은
oj-header.php에서 <b><pre>$OJ_MAIN_COLOR = "info"; //info success danger warning primary</pre></b>을 수정하시면 됩니다. (기본 디렉토리는 admin)

본 스킨은 교육용+비상업적인 용도로만 사용가능하며 GPL 2.0 라이선스를 따릅니다.



# 이 스킨은 hustoj의 원본소스를 수정하여 제작되었습니다.
- 수정 : Leejunghwan(bigdipper81@nate.com)
- 주원아 자연은 원래 느린거란다.

## 변경된 기능
- 상단 헤더부분을 oj-header.php로 변경 통합
- 충격적이었던 problemset.php의 검색기능 대폭수정(코어변경 필요)
- 일부 라이브러리 CDN으로 대체
- 차트 디자인 변경
- 자주묻는 질문-컴파일러 정보를 서버에서 받아 출력합니다.
- fontawsome사용
- 관리자페이지를 웹표준에 맞춰 제작하려 했습니다.
- 문제 등록화면에서 scrollspy를 추가했습니다.
- 대회등록 UI를 개선했습니다.
- 한 줄 공지사항(msg.txt)을 개선했습니다. html 사용이 가능합니다.
- 모바일 반응성을 개선했습니다.
- 문제보기 페이지 디자인 수정


## 추가된 기능
- 관리자-db_config.php을 웹에서 수정할 수 있게 했습니다.
- 관리자-lang.ko.php을 웹에서 수정할 수 있게 했습니다.
- 에디터를 변경하였습니다. - 이미지 복붙 가능(필드타입 변경필요)
- 시스템 정보를 출력하도록 하였습니다.
- 랭킹순위를 표시합니다.
- 공지사항 페이지를 제작하였습니다.

## 사용된 라이브러리
- chart.js
- jQuery 3.6
- fontawsome
- bootstrap 4.6
- tinyMCE

### 도움을 주신 분들
- 이주원(기차, 버스, 비행기, 고기를 좋아함)
- Moon (주종족 저그)
- hebelle (투자귀재)
