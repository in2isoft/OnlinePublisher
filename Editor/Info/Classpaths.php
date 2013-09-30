<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

$classes = array (
  'DatabaseUtil' => 'Utilities/DatabaseUtil.php',
  'Dates' => 'Utilities/Dates.php',
  'DOMUtils' => 'Utilities/DOMUtils.php',
  'EventUtils' => 'Utilities/EventUtils.php',
  'GuiUtils' => 'Utilities/GuiUtils.php',
  'MarkupUtils' => 'Utilities/MarkupUtils.php',
  'SelectBuilder' => 'Utilities/SelectBuilder.php',
  'StopWatch' => 'Utilities/StopWatch.php',
  'StringBuilder' => 'Utilities/StringBuilder.php',
  'Strings' => 'Utilities/Strings.php',
  'TextDecorator' => 'Utilities/TextDecorator.php',
  'ValidateUtils' => 'Utilities/ValidateUtils.php',
  'AbstractObjectTest' => 'Tests/AbstractObjectTest.php',
  'AuthenticationTemplate' => 'Templates/AuthenticationTemplate.php',
  'AuthenticationTemplateController' => 'Templates/AuthenticationTemplateController.php',
  'CalendarTemplateController' => 'Templates/CalendarTemplateController.php',
  'DocumentTemplateController' => 'Templates/DocumentTemplateController.php',
  'DocumentTemplateEditor' => 'Templates/DocumentTemplateEditor.php',
  'GuestbookTemplateController' => 'Templates/GuestbookTemplateController.php',
  'HtmlTemplateController' => 'Templates/HtmlTemplateController.php',
  'SearchTemplate' => 'Templates/SearchTemplate.php',
  'SearchTemplateController' => 'Templates/SearchTemplateController.php',
  'SitemapTemplateController' => 'Templates/SitemapTemplateController.php',
  'TemplateController' => 'Templates/TemplateController.php',
  'WeblogTemplate' => 'Templates/WeblogTemplate.php',
  'WeblogTemplateController' => 'Templates/WeblogTemplateController.php',
  'AuthenticationService' => 'Services/AuthenticationService.php',
  'CacheService' => 'Services/CacheService.php',
  'ClassService' => 'Services/ClassService.php',
  'ClientService' => 'Services/ClientService.php',
  'ClipboardService' => 'Services/ClipboardService.php',
  'ConfigurationService' => 'Services/ConfigurationService.php',
  'DesignService' => 'Services/DesignService.php',
  'EventService' => 'Services/EventService.php',
  'FileService' => 'Services/FileService.php',
  'FileSystemService' => 'Services/FileSystemService.php',
  'FontService' => 'Services/FontService.php',
  'FrameService' => 'Services/FrameService.php',
  'HeartBeatService' => 'Services/HeartBeatService.php',
  'HierarchyService' => 'Services/HierarchyService.php',
  'ImageService' => 'Services/ImageService.php',
  'IssueService' => 'Services/IssueService.php',
  'JsonService' => 'Services/JsonService.php',
  'LogService' => 'Services/LogService.php',
  'MailService' => 'Services/MailService.php',
  'NewsService' => 'Services/NewsService.php',
  'ObjectLinkService' => 'Services/ObjectLinkService.php',
  'ObjectService' => 'Services/ObjectService.php',
  'OnlineObjectsService' => 'Services/OnlineObjectsService.php',
  'OptimizationService' => 'Services/OptimizationService.php',
  'PageService' => 'Services/PageService.php',
  'PartService' => 'Services/PartService.php',
  'PublishingService' => 'Services/PublishingService.php',
  'RelationsService' => 'Services/RelationsService.php',
  'RemoteDataService' => 'Services/RemoteDataService.php',
  'RenderingService' => 'Services/RenderingService.php',
  'ReportService' => 'Services/ReportService.php',
  'SchemaService' => 'Services/SchemaService.php',
  'SettingService' => 'Services/SettingService.php',
  'StatisticsService' => 'Services/StatisticsService.php',
  'TemplateService' => 'Services/TemplateService.php',
  'TestService' => 'Services/TestService.php',
  'ToolService' => 'Services/ToolService.php',
  'WaterusageService' => 'Services/WaterusageService.php',
  'XmlService' => 'Services/XmlService.php',
  'XslService' => 'Services/XslService.php',
  'ZipService' => 'Services/ZipService.php',
  'FilePart' => 'Parts/FilePart.php',
  'FilePartController' => 'Parts/FilePartController.php',
  'FormulaPart' => 'Parts/FormulaPart.php',
  'FormulaPartController' => 'Parts/FormulaPartController.php',
  'HeaderPart' => 'Parts/HeaderPart.php',
  'HeaderPartController' => 'Parts/HeaderPartController.php',
  'HorizontalrulePart' => 'Parts/HorizontalrulePart.php',
  'HorizontalrulePartController' => 'Parts/HorizontalrulePartController.php',
  'HtmlPart' => 'Parts/HtmlPart.php',
  'HtmlPartController' => 'Parts/HtmlPartController.php',
  'ImagegalleryPart' => 'Parts/ImagegalleryPart.php',
  'ImagegalleryPartController' => 'Parts/ImagegalleryPartController.php',
  'ImagePart' => 'Parts/ImagePart.php',
  'ImagePartController' => 'Parts/ImagePartController.php',
  'ListingPart' => 'Parts/ListingPart.php',
  'ListingPartController' => 'Parts/ListingPartController.php',
  'ListPart' => 'Parts/ListPart.php',
  'ListPartController' => 'Parts/ListPartController.php',
  'MailinglistPart' => 'Parts/MailinglistPart.php',
  'MailinglistPartController' => 'Parts/MailinglistPartController.php',
  'MapPart' => 'Parts/MapPart.php',
  'MapPartController' => 'Parts/MapPartController.php',
  'MoviePart' => 'Parts/MoviePart.php',
  'MoviePartController' => 'Parts/MoviePartController.php',
  'NewsPart' => 'Parts/NewsPart.php',
  'NewsPartController' => 'Parts/NewsPartController.php',
  'Part' => 'Parts/Part.php',
  'PartContext' => 'Parts/PartContext.php',
  'PartController' => 'Parts/PartController.php',
  'PersonPart' => 'Parts/PersonPart.php',
  'PersonPartController' => 'Parts/PersonPartController.php',
  'PosterPart' => 'Parts/PosterPart.php',
  'PosterPartController' => 'Parts/PosterPartController.php',
  'RichtextPart' => 'Parts/RichtextPart.php',
  'RichtextPartController' => 'Parts/RichtextPartController.php',
  'TablePart' => 'Parts/TablePart.php',
  'TablePartController' => 'Parts/TablePartController.php',
  'TextPart' => 'Parts/TextPart.php',
  'TextPartController' => 'Parts/TextPartController.php',
  'Address' => 'Objects/Address.php',
  'Cachedurl' => 'Objects/Cachedurl.php',
  'Calendar' => 'Objects/Calendar.php',
  'Calendarsource' => 'Objects/Calendarsource.php',
  'Design' => 'Objects/Design.php',
  'Emailaddress' => 'Objects/Emailaddress.php',
  'Event' => 'Objects/Event.php',
  'Feedback' => 'Objects/Feedback.php',
  'File' => 'Objects/File.php',
  'Filegroup' => 'Objects/Filegroup.php',
  'Image' => 'Objects/Image.php',
  'Imagegroup' => 'Objects/Imagegroup.php',
  'Issue' => 'Objects/Issue.php',
  'Issuestatus' => 'Objects/Issuestatus.php',
  'Mailinglist' => 'Objects/Mailinglist.php',
  'Milestone' => 'Objects/Milestone.php',
  'News' => 'Objects/News.php',
  'Newsgroup' => 'Objects/Newsgroup.php',
  'Newssource' => 'Objects/Newssource.php',
  'Newssourceitem' => 'Objects/Newssourceitem.php',
  'Pageblueprint' => 'Objects/Pageblueprint.php',
  'Path' => 'Objects/Path.php',
  'Person' => 'Objects/Person.php',
  'Persongroup' => 'Objects/Persongroup.php',
  'Personrole' => 'Objects/Personrole.php',
  'Phonenumber' => 'Objects/Phonenumber.php',
  'Problem' => 'Objects/Problem.php',
  'Product' => 'Objects/Product.php',
  'Productgroup' => 'Objects/Productgroup.php',
  'Productoffer' => 'Objects/Productoffer.php',
  'Producttype' => 'Objects/Producttype.php',
  'Project' => 'Objects/Project.php',
  'Remotepublisher' => 'Objects/Remotepublisher.php',
  'Review' => 'Objects/Review.php',
  'Securityzone' => 'Objects/Securityzone.php',
  'Task' => 'Objects/Task.php',
  'Testphrase' => 'Objects/Testphrase.php',
  'User' => 'Objects/User.php',
  'Watermeter' => 'Objects/Watermeter.php',
  'Waterusage' => 'Objects/Waterusage.php',
  'Weblogentry' => 'Objects/Weblogentry.php',
  'Webloggroup' => 'Objects/Webloggroup.php',
  'Feed' => 'Network/Feed.php',
  'FeedItem' => 'Network/FeedItem.php',
  'FeedParser' => 'Network/FeedParser.php',
  'FeedSerializer' => 'Network/FeedSerializer.php',
  'FileUpload' => 'Network/FileUpload.php',
  'HttpClient' => 'Network/HttpClient.php',
  'HttpRequest' => 'Network/HttpRequest.php',
  'HttpResponse' => 'Network/HttpResponse.php',
  'ImportResult' => 'Network/ImportResult.php',
  'RemoteData' => 'Network/RemoteData.php',
  'RemoteFile' => 'Network/RemoteFile.php',
  'UserAgentAnalyzer' => 'Network/UserAgentAnalyzer.php',
  'WatermeterSummary' => 'Modules/Water/WatermeterSummary.php',
  'StatisticsQuery' => 'Modules/Statistics/StatisticsQuery.php',
  'ReviewCombo' => 'Modules/Review/ReviewCombo.php',
  'ReviewService' => 'Modules/Review/ReviewService.php',
  'NewsArticle' => 'Modules/News/NewsArticle.php',
  'LinkInfo' => 'Modules/Links/LinkInfo.php',
  'LinkQuery' => 'Modules/Links/LinkQuery.php',
  'LinkService' => 'Modules/Links/LinkService.php',
  'LinkView' => 'Modules/Links/LinkView.php',
  'Inspection' => 'Modules/Inspection/Inspection.php',
  'InspectionService' => 'Modules/Inspection/InspectionService.php',
  'Gradient' => 'Modules/Images/Gradient.php',
  'ImageTransformationRecipe' => 'Modules/Images/ImageTransformationRecipe.php',
  'ImageTransformationService' => 'Modules/Images/ImageTransformationService.php',
  'Graph' => 'Modules/Graphs/Graph.php',
  'GraphNode' => 'Modules/Graphs/GraphNode.php',
  'Graphviz' => 'Modules/Graphs/Graphviz.php',
  'Entity' => 'Model/Entity.php',
  'Frame' => 'Model/Frame.php',
  'Hierarchy' => 'Model/Hierarchy.php',
  'HierarchyItem' => 'Model/HierarchyItem.php',
  'Link' => 'Model/Link.php',
  'Object' => 'Model/Object.php',
  'ObjectLink' => 'Model/ObjectLink.php',
  'Page' => 'Model/Page.php',
  'PartLink' => 'Model/PartLink.php',
  'SpecialPage' => 'Model/SpecialPage.php',
  'Template' => 'Model/Template.php',
  'Zend' => 'Libraries/Zend.php',
  'DiagramData' => 'Interface/DiagramData.php',
  'DiagramEdge' => 'Interface/DiagramEdge.php',
  'DiagramNode' => 'Interface/DiagramNode.php',
  'In2iGui' => 'Interface/In2iGui.php',
  'ItemsWriter' => 'Interface/ItemsWriter.php',
  'ListWriter' => 'Interface/ListWriter.php',
  'GoogleAnalytics' => 'Integration/GoogleAnalytics.php',
  'CSVWriter' => 'Formats/CSVWriter.php',
  'DBUCalendar' => 'Formats/DBUCalendar.php',
  'DBUCalendarEvent' => 'Formats/DBUCalendarEvent.php',
  'DBUCalendarParser' => 'Formats/DBUCalendarParser.php',
  'HtmlDocument' => 'Formats/HtmlDocument.php',
  'HtmlTableParser' => 'Formats/HtmlTableParser.php',
  'VCalendar' => 'Formats/VCalendar.php',
  'VCalParser' => 'Formats/VCalParser.php',
  'VCalSerializer' => 'Formats/VCalSerializer.php',
  'VEvent' => 'Formats/VEvent.php',
  'VRecurrenceRule' => 'Formats/VRecurrenceRule.php',
  'ZipArchive' => 'Formats/ZipArchive.php',
  'ZipArchiveItem' => 'Formats/ZipArchiveItem.php',
  'ClassInfo' => 'Core/ClassInfo.php',
  'ClassPropertyInfo' => 'Core/ClassPropertyInfo.php',
  'ClassRelationInfo' => 'Core/ClassRelationInfo.php',
  'Database' => 'Core/Database.php',
  'ExternalSession' => 'Core/ExternalSession.php',
  'InternalSession' => 'Core/InternalSession.php',
  'Log' => 'Core/Log.php',
  'PageQuery' => 'Core/PageQuery.php',
  'Query' => 'Core/Query.php',
  'Request' => 'Core/Request.php',
  'Response' => 'Core/Response.php',
  'SearchResult' => 'Core/SearchResult.php',
  'SystemInfo' => 'Core/SystemInfo.php',
  'TemporaryFolder' => 'Core/TemporaryFolder.php',
)
?>