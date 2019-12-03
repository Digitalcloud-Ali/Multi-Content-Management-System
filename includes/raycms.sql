-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2012 at 07:07 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rayicecms`
--

-- --------------------------------------------------------

--
-- Table structure for table `adposting`
--

CREATE TABLE IF NOT EXISTS `adposting` (
  `adpostingid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `adpostingurl` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`adpostingid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `adposting`
--

INSERT INTO `adposting` (`adpostingid`, `title`, `description`, `imageurl`, `adpostingurl`, `owner`, `date`, `catename`, `status`, `rating`, `views`, `position`, `editorpick`, `users`) VALUES
(4, 'PS3 For Sale', '<div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><p>Hello, ps3 is for sale, i have it and it is brand new product, its price is 30,000 pkr. Contact me on 009232188482.</p></div></div></div></div></div>', 'ps3.jpg', NULL, 'admin', '12-12-2012', 'business', 'published', 0, 41, 'normal', 'yes', 'admin'),
(5, 'Rayice Business1', '<div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><p>A new rayice business is on the way, the business is for the purpose of creating stuffs and products for online things. We are providing this business through out the world, anyone can contact us at ask@rayice.com</p></div></div></div></div>', 'logo.png', NULL, 'admin', '14-12-2012', 'jobs', 'published', 0, 77, 'normal', 'yes', 'admin'),
(6, 'Mobile for sell', '<div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><p>i m selling my mobile, any one want please contact me</p></div></div></div></div></div></div>', '1.jpg', 'www.rayice.com', 'admin', '12-22-1212', 'jobs', 'published', 0, 8, 'featured', 'yes', 'admin'),
(9, 'adposting', 'asdad', '680002053xmD7.jpg', NULL, 'admin', '18-05-2012', 'jobs', 'pending', 0, 0, 'normal', 'yes', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `blogid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `metadesc` varchar(100) NOT NULL,
  `metakey` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  PRIMARY KEY (`blogid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`blogid`, `title`, `description`, `photo`, `dates`, `metadesc`, `metakey`, `catename`, `status`, `position`, `users`, `rating`, `views`) VALUES
(1, 'The Only Reason Companies Delete Emails Is To Destroy Evidence', '\r\n<p>The News Corp. phone-hacking scandal continues to spiral out of control, sweeping up more and more of the companies employees and executives. In the UK,&nbsp;<a href="http://mediagazer.com/#a120211p4">8 people&nbsp;were arrested</a>, including five News Corp journalists, in the broadening scandal, which may embroil deputy COO James MurdochÃ¢â‚¬â€RupertÃ¢â‚¬â„¢s son and heir-apparent. A&nbsp;paper copy of a deleted email found in a crate ties James Murdoch directly to the events under investigation, which involved the routine and illegal hacking of phone voicemails on behalf of a News Corp publication.</p>\r\n<p>This email evidence would never have been found if it wasnÃ¢â‚¬â„¢t printed out because News Corp, like many corporations, regularly deletes archived emails. It is standard practice, but the technical reasons given for deleting emails are usually not the real reason they are eliminated. The only real reason to destroy old emails is to avoid liability and future lawsuits.</p>\r\n<p>Reading the&nbsp;<a href="http://www.nytimes.com/2012/02/12/world/europe/a-2008-e-mail-at-the-heart-of-a-hacking-scandal.html?_r=1&amp;hpw=&amp;pagewanted=all">account</a>&nbsp;in the&nbsp;<em>New York Times</em>&nbsp;about this newly discovered email from former&nbsp;<em>News of the World</em>&nbsp;editor Colin Myler to James Murdoch, which dates back to 2008, you get a sense it wasnÃ¢â‚¬â„¢t meant to be found.</p>\r\n<blockquote>\r\n  <p>Mr. MylerÃ¢â‚¬â„¢s electronic copy had been lost Ã¢â‚¬Å“in a hardware failureÃ¢â‚¬Â on March 18, 2010,Ã¢â‚¬Â while Mr. MurdochÃ¢â‚¬â„¢s electronic copy had been deleted on Jan. 15, 2011 during an Ã¢â‚¬Å“e-mail stabilization and modernization program.Ã¢â‚¬Â</p>\r\n  <p>Big corporations routinely delete old e-mails. Between April 2010 and July 2011, News International discussed e-mail deletion with&nbsp;<a title="The companyÃ¢â‚¬â„¢s Web site" href="http://www.hcltech.com/">HCL Technologies</a>, which manages its e-mail system, on nine occasions, according to a letter HCL wrote to Parliament last summer.</p>\r\n  <p>Most of the reasons were mundane. But in January 2011, HCL said, News International asked whether HCL was capable of helping Ã¢â‚¬Å“truncateÃ¢â‚¬Â Ã¢â‚¬â€ meaning delete Ã¢â‚¬â€ Ã¢â‚¬Å“a particular databaseÃ¢â‚¬Â in the e-mail system. The question came shortly after disclosures in a civil suit brought by the actress Sienna Miller raised fears that material about widespread phone hacking at The News of the World might become public.</p>\r\n</blockquote>\r\n<p>Companies know that incriminating evidence always exists in emails because emails document the conversations and decision-making that goes on in all organizations. But they need a justification other than Ã¢â‚¬Å“We donÃ¢â‚¬â„¢t want to get caught.Ã¢â‚¬Â So thatÃ¢â‚¬â„¢s how you get corporate doublespeak like Ã¢â‚¬Å“e-mail stabilization and modernizationÃ¢â‚¬Â programs, with its vague suggestion that there is a technical reason to delete old emails, as if a companyÃ¢â‚¬â„¢s entire email system might crash under the weight of old emails stored on a server.</p>\r\n<p>LetÃ¢â‚¬â„¢s just be clear here. By putting in place policies to routinely delete old email archives, companies are protecting themselves from future incrimination. And News Corp isnÃ¢â‚¬â„¢t the only company that does this, by any means.&nbsp;ItÃ¢â‚¬â„¢s a preventative measure. But it only works if they destroy any incriminating emails&nbsp;<em>before</em>&nbsp;they are caught. Once an investigation starts and the prospect of subpoenas arise, destroying emails is no longer a legal option. In this case, that may come back to bite News Corp.</p>\r\n', 'burninglaptopfun.jpg', '12-02-2012', 'The Only Reason Companies Delete Emails Is To Destroy Evidence', 'The Only Reason ,Companies Delete, Emails Is To, Destroy Evidence', 'investment', 'published', 'normal', 'admin', 0, 15),
(2, 'LinkedIn Plans Mobile Ads This Year', '<div class="oneone"><p>LinkedIn just became the second social media giant this month to signal plans for ads in mobile.During its quarterly earnings call Thursday, CEO Jeff Weiner said mobile access represents an ever-growing share of time users spend with LinkedIn. He said the company is investigating ways to monetize those page views.Ã¢â‚¬Å“WeÃ¢â‚¬â„¢re going to start to run some tests with regard to advertising some of our marketing solutions within the mobile environment,Ã¢â‚¬Â Weiner said.</p>\r\n<p>When those ads will appear and what theyÃ¢â‚¬â„¢ll look like is anybodyÃ¢â‚¬â„¢s guess. LinkedIn did not immediately reply to a request for more information.WeinerÃ¢â‚¬â„¢s remarks come one week after reports surfaced that Facebook will begin serving ads to its 420 million-strong mobile audience. The&nbsp;<em>Financial Times</em>&nbsp;cited&nbsp;<a href="http://www.ft.com/intl/cms/s/0/a0bd164c-500c-11e1-a3ac-00144feabdc0.html#axzz1liP1rQH0" target="_blank">unnamed sources</a>&nbsp;who pinpointed a launch date of early March, ahead of the companyÃ¢â‚¬â„¢s planned public offering in May. Those sources referred to the pending ads as Ã¢â‚¬Å“Ã¢â‚¬Ëœfeatured storiesÃ¢â‚¬â„¢ in the news feed.Ã¢â‚¬Â</p>\r\n<p>In August, LinkedIn revamped its mobile presence with new apps for Android and iOS and a new mobile site. As a result, Weiner said mobile is the fastest growing service on LinkedIn, accounting for more than 15% of total unique number visits.Ã¢â‚¬Å“Because members need LinkedIn to work wherever they work weÃ¢â‚¬â„¢ve made great strides in our platform in mobile offerings in 2011,Ã¢â‚¬Â said Weiner.&nbsp;LinkedIn is the number three social networking platform in the U.S., with 33.5 million unique viewers, according to comScore. Throughout much of 2011 the company vied with Twitter for number two, but Twitter pulled away by yearÃ¢â‚¬â„¢s end with 37.5 million unique viewers.</p>\r\n</div>', '1360linkedin.jpg', '12-02-2012', 'LinkedIn Plans Mobile Ads This Year', 'LinkedIn Plans ,Mobile Ads, This Year', 'internet', 'published', 'normal', 'admin', 0, 1),
(3, 'Should Mark Zuckerberg Think Twice About Establishing A Dynasty?', '<div class="oneone"><div class="oneone"><div class="oneone"><p>Congratulations Facebook! You have made history and changed the world. So, here are some thoughts from one of your biggest fans. Like the rest of the planet, I love Facebook and use it every day. So, there may never be a better time than now, when things are going really well, to add a dose of humility and perspective to the Facebook conversation.</p>\r\n<p>Remember the movie Gladiator? Commodus, the bad son, murdered his aging father, Emperor Marcus Aurelius, preventing him from passing the empire down to his adopted good son, Maximus. Thus, instead of carrying on a centuries old tradition of merit-based succession, power passed to an unworthy blood relative and corruption followed. </p>\r\n<p>What does this have to do with Facebook founder Mark Zuckerberg? Well, according to the $5 billion IPO filing, legal documents give him absolute control over the post-IPO company, even beyond the grave, just like a Roman emperor. That kind of power can have unintended consequences.The story of FacebookÃ¢â‚¬â„¢s spectacularly successful founder serves as a blueprint for others who hope to create corporate dynasties. Still, both he and those who seek to emulate him would be wise to take the counsel of history and establish at least a minimally representative corporate governance structure that includes one or more independent board members.Facebook came of age after GoogleÃ¢â‚¬â„¢s founders obtained super majority control at IPO, followed by LinkedIn, Groupon and Zynga (and more). In its early days, Sean Parker, a serial entrepreneur (Napster, Plaxo) who played an important early role as a confidante to Mr. Zuckerberg, helped convince him of the importance of founders maintaining control. As such, the Facebook founder has long dominated his board of directors, appointing three out of five seats.</p>\r\n<p>Now, Facebook takes the founder-control trend to the extreme. By converting his shares into a class of super-voting stock at IPO, and designating Facebook as a Ã¢â‚¬Å“controlled company,Ã¢â‚¬Â Zuckerberg will not only control 57.1% of the vote, but will also have the legal right to name 100% of the board of directors. He can also designate whomever he chooses as the successor to his corporate authority.</p>\r\n<p>The unintended consequence of such absolute control may be the opposite of what Zuckerberg hopes. It isnÃ¢â‚¬â„¢t a stretch to believe that he genuinely wants control in order to keep the business focused on the long-term social mission he described in his letter to shareholders, rather than the short-term gains often demanded by financial managers. What may happen instead is that the post-IPO business finds itself subject to whimsical decision-making and vulnerable to the inevitable securities lawsuits.</p>\r\n<p>When founder-controlled companies sell shares to the public, they should plan for the possibility of the emperor at some point Ã¢â‚¬Å“having no clothes.Ã¢â‚¬Â Eventually, even the best founders can lose their mojo, or appoint a successor who proves unequal to the task. This is when having independent views and fiduciaries can help shield the business from liability and guide it to a better place, even without legal control.</p>\r\n<p>Of course, founder-controlled companies are often extraordinarily successful. In the United States, the founding family is an influential investor in more than one-third of the Standard &amp; PoorÃ¢â‚¬â„¢s 500 companies. Founding family owned companies tend to do well because of the long-term influence, interest, and investment of owners who are motivated by mission, not just by financial gain.</p>\r\n<p>For example, Ford Motor Company has managed to sustain a profitable founding family business since Henry Ford incorporated it in 1903. Since 1956, the Ford family has wielded at least 40% of the companyÃ¢â‚¬â„¢s voting rights by setting up a system to ensure that only family members can own Class B stock. The familyÃ¢â‚¬â„¢s voting power includes the exclusive right to approve a merger, sale, or liquidation of the company.</p>\r\n<p>In their desire to control but not stifle the business, the Fords enlisted qualified advisers, including original counsel Clifford Longley, investor Goldman Sachs, and independent directors. And it worked; Ford has survived multiple recessions, including the most recent economic downturn. Ford was the only American carmaker that didnÃ¢â‚¬â„¢t need a government bailout.</p>\r\n<p>Mr. Zuckerberg has so far made a different choice about the governance of Facebook. While appointing Sheryl Sandberg as a strong #2 has been brilliant, what will he do when she moves on? He and his heirs can exercise more corporate power post-IPO than when it was private. Opting to function as a Ã¢â‚¬Å“controlled company,Ã¢â‚¬Â Facebook will be exempt from the customary stock exchange corporate governance rules that apply to the vast majority of public companies.</p>\r\n<p>From the Facebook prospectus (S-1):</p>\r\n<p>Because we qualify as a Ã¢â‚¬Å“controlled companyÃ¢â‚¬Â under the corporate governance rules for publicly-listed companies, we are not required to have a majority of our board of directors be independent, nor are we required to have a compensation committee or an independent nominating function.</p>\r\n<p>Instead of a nominating committee for directors, all directors will be selected, removed and replaced by Mr. Zuckerberg, who is also imbued with the power to unilaterally choose a successor.</p>\r\n<p>Mr. Zuckerberg has the ability to control the outcome of matters submitted to our stockholders for approval, including the election of directors and any merger, consolidation, or sale of all or substantially all of our assets Ã¢â‚¬Â¦ Additionally, in the event that Mr. Zuckerberg controls our company at the time of his death, control may be transferred to a person or entity that he designates as his successor.<br>\r\n  While Larry Page, Sergey Brin, and Eric Schmidt at Google also maintain control, with<br>\r\n  majority ownership and voting rights, the triumvirate approach has balanced governance, and none of their rights extends to the power to appoint and remove independent directors at will. The same goes for Reid Hoffman at LinkedIn, Andrew Mason at Groupon and Mark Pincus at Zynga.</p></div></div></div>', '2joaquin-phoenix.jpg', '12-02-2012', 'Should Mark Zuckerberg Think Twice About Establishing A Dynasty?', 'Should Mark Zuckerberg Think, Twice, About Establishing, A Dynasty?', 'internet', 'published', 'normal', 'admin', 0, 2),
(4, 'Majority Of Google TV App Install Base From Pre-Loads', '<p>New&nbsp;<a href="http://www.xyologic.com/blog/google-tv-apps-fact-sheet/">data</a>&nbsp;from app search firm&nbsp;<a href="http://www.xyologic.com/">Xyologic</a>&nbsp;released this morning paints a picture of the relative success (or lack thereof) of the Google TV platform. By examining the install base for the apps exclusive to the Google TV platform, itÃ¢â‚¬â„¢s clear that the Google media center product is still only attracting a niche crowd of early adopters. Of the nearly 4.8 million installed apps that are exclusive to Google TV, only 352,000 of them represent user downloads Ã¢â‚¬â€œ the remaining 4,441,000 are pre-installed applications.</p>\r\n<p>Xyologic, which has been following Google TV since August 2011, notes that there are 64 total apps which are exclusive to the Google TV platform, forming a total install base of 4,793,000. These would be the apps most attractive to the platformÃ¢â‚¬â„¢s end users, as theyÃ¢â‚¬â„¢ve been customized and designed for the big screen. Watching for trends among this group can give you insight into the platformÃ¢â‚¬â„¢s overall health and performance as well as Google TV user preferences.</p>\r\n<p>Not surprisingly, the six pre-installed apps account for the top six apps by install base (4,441,000 installs). These include Napster&nbsp;for Google TV, Pandora&nbsp;for Google TV, CNBC&nbsp;for Google TV, TV &amp; Movies for Google TV, Photos for Google TV and Twitter for Google TV.</p>\r\n<p>The remaining exclusive Google TV apps that round out the top 10 include Redux&nbsp;for Google TV, CNNMoney&nbsp;for Google TV, Maps&nbsp;for Google TV, MotorTrend and Thuuz Sports&nbsp;for Google TV. These account for just 58,000 installs.</p>\r\n<p><img google-tv-apps="google-tv-apps" src="http://tctechcrunch2011.files.wordpress.com/2012/02/google-tv-apps.png?w=640" alt=""></p>\r\n<p>Xyologic also points out that Google TVÃ¢â‚¬â„¢s exclusive apps have low ratings Ã¢â‚¬â€œ something which seems to confirm Ã¢â‚¬Å“an underwhelming experience for users,Ã¢â‚¬Â the company says in its report. Meanwhile, non-exclusive Google TV apps are seeing higher ratings but significantly lower number of downloads. The top non-exclusive apps currently include&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3anet.tyx.classyfireplace">Classy Fireplace</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.lsgvgames.slideandfly">Dragon, Fly! Free</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.shinebox.android.cuevana">CuevanAndroid,</a>&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.google.android.music">Google Music</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.videon.android.mediaplayer">aVia Media Player</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.mobilityware.solitaire">Solitaire</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.bestcoolfungamesfreegameappcreation.fireworksgame">Fireworks Ã¢â‚¬â€œ the Best Free Game</a>,<a href="http://www.xyologic.com/search/Android/ANY?q=app%3ade.shapeservices.impluslite">IM+</a>,&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.buddytv.android">BuddyTV</a>&nbsp; and&nbsp;<a href="http://www.xyologic.com/search/Android/ANY?q=app%3acom.alexvas.dvr">tinyCam Monitor Free</a>.</p>\r\n<p><em>TL;DR: Google TV is not very popular.&nbsp;</em></p>\r\n<p>So far, only LG and Sony have shipped Google TV devices, and&nbsp;despite criticism&nbsp;regarding their looks, pricing and Google TV itself, both have decided to stick with the platform for now. At JanuaryÃ¢â‚¬â„¢s CES,&nbsp;LG showed off its new Google TV set, for example, but hedged its bets by also rolling out its own Smart TV platform in the event of a total Google TV flop. Sony, meanwhile,&nbsp;launched a new Blu-ray player&nbsp;with Google TV baked in.</p>\r\n<p>Google, too, continues to try and drum up interest for Google TV. Late last week, it teased Ã¢â‚¬Å“big improvementsÃ¢â‚¬Â for Google TV, then proceeded to underwhelm. The big news was an improved YouTube app. Hooray.</p>\r\n<p>In an effort to continue tracking this space, Xyologic has&nbsp;launched&nbsp;an early version of its search service specifically for Google TV apps today which initially includes 170 apps in its index.</p>\r\n<p>But the firmÃ¢â‚¬â„¢s conclusion as to what this data means, mirrors that of most industry observers: it may be the early days for Google TV, but the industry is now moving to Smart TVs Ã¢â‚¬â€œ those with apps, streaming, browsing, conferencing, etc. built in. Unless Google TV can find a foothold as the preferred Smart TV backend, its chances for success, especially if that&nbsp;rumored Apple Ã¢â‚¬Å“iTVÃ¢â‚¬Âlaunches this year Ã¢â‚¬â€œ could be slim.</p>', '3google-tv_update.jpg', '13-02-2012', 'Majority Of Google TV App Install Base From Pre-Loads', 'Majority Of, Google TV App, Install Base, From Pre-Loads', 'technology', 'published', 'normal', 'admin', 0, 11),
(5, 'Meet SamsungÃ¢â‚¬â„¢s First Android 4.0 Tablet: The 7-Inch Galaxy Tab 2', '<p>Samsung is no slouch when it comes to producing smaller tablets Ã¢â‚¬â€ their first foray into the Android tab market was the 7-inch Galaxy Tab, and they recently revisited that form factor with the Galaxy Tab 7.0 Plus. In fact, Samsung seems to love the concept of a 7-inch tablet so much that they have announced their first <a href="http://www.samsungmobilepress.com/2012/02/13/Samsung''s-new-GALAXY-Tab-2-(7.0)-offers-optimal-multimedia-experiences-in-life">Ice Cream Sandwich-powered tablet</a> will be the (take a guess!) 7-inch Galaxy Tab 2.</p>\r\n<p>While the news of a new Android 4.0 tablet is enough to get some gadget fans all hot and bothered, the Galaxy Tab 2Ã¢â‚¬Â²s spec sheet doesnÃ¢â‚¬â„¢t illustrate much technical improvement over its forebears. It features the same sort of 7-inch PLS display and 4,000 mAh battery as the Galaxy Tab 7.0 Plus, not to mention that they both sport a 3-megapixel rear camera and a VGA front-facer.</p>\r\n<p>WhatÃ¢â‚¬â„¢s more, the processor actually looks like a bit of a step down on paper: the Galaxy Tab 2 touts a 1 GHz dual-core processor, as opposed to the 1.2 GHz processor in the 7.0 Plus. IÃ¢â‚¬â„¢m sure Samsung had their reasons for running with a different chipset Ã¢â‚¬â€ better battery life, or perhaps improved performance thanks to a new SoC Ã¢â‚¬â€ but itÃ¢â‚¬â„¢s still a bit odd to see a major player kick off their new line of Android 4.0 tablets without something more flashy.</p>\r\n<p>And maybe thatÃ¢â‚¬â„¢s part of the plan. More than a few people have pointed out that AmazonÃ¢â‚¬â„¢s own wallet-friendly 7-inch tablet has been immensely popular, which may be putting pressure on other companies to try and strike a precarious balance between price and performance. IÃ¢â‚¬â„¢m looking forward to seeing how exactly Samsung will try and position their the little bugger, and the wait shouldnÃ¢â‚¬â„¢t be too bad. The Galaxy Tab will officially debut first in the UK next month, and will presumably begin its world tour not too long afterwards.</p>', '4galaxy-tab-2.jpg', '13-02-2012', 'Meet SamsungÃ¢â‚¬â„¢s First Android 4.0 Tablet: The 7-Inch Galaxy Tab 2', 'Meet SamsungÃ¢â‚¬â„¢s, First Android 4.0, Tablet: The 7-Inch, Galaxy Tab 2', 'technology', 'published', 'featured', 'admin', 0, 0),
(6, 'Blinkx Replaces Truveo To Power AOL Video Search', '\r\n<p>British video search company&nbsp;<a href="http://www.blinkx.com/">Blinkx</a>&nbsp;saw its<a href="http://www.google.com/finance?q=LON%3ABLNX">stock</a>&nbsp;spike briefly this morning, following an announcement that it will power AOLÃ¢â‚¬â„¢s video search. AOL is one of the largest video destinations on the Web, with about 450 million video views per month according to&nbsp;<a href="http://www.comscore.com/Press_Events/Press_Releases/2012/1/comScore_Releases_December_2011_U.S._Online_Video_Rankings">comScore</a>.</p>\r\n<p>Blinkx will also incorporate AOLÃ¢â‚¬â„¢s premium videos in its own search engine. (Presumably, that will include TCTV videos, since we are owned by AOL). Blinkx itself attracts 55 million U.S. video searchers a month. AOLÃ¢â‚¬â„¢s video properties are watched by about 40 million unique viewers (comScore), so the deal could significantly expand blinkxÃ¢â‚¬â„¢s reach.</p>\r\n<p>But doesnÃ¢â‚¬â„¢t AOL already have its own video search technology? Back in 2006 it&nbsp;acquired&nbsp;a video search engine called Truveo. Up until recently, Truveo was powering all of AOLÃ¢â‚¬â„¢s video search. But itÃ¢â‚¬â„¢s been on life support for months, and now with this deal the plug is being pulled on Truveo.</p>\r\n<p>Failed acquisitions aside, AOL wants its videos to reach the broadest audience. An internal-only video search engine doesnÃ¢â‚¬â„¢t do much to reach new audiences. Of course, most people search for videos on Google, not blinkx. And somehow YouTube always seems to turn up as the top video results on Google. If your videos arenÃ¢â‚¬â„¢t on YouTube, they are sort of invisible. But if they are on YouTube, you have to cut them in on the ad revenues. So media companies are trying to push video viewers to their own sites through deals like the one AOL just did with blinkx.</p>\r\n', '5blinkx-chart.jpg', '13-02-2012', 'Blinkx Replaces Truveo To Power AOL Video Search', 'Blinkx Replaces Truveo, To Power AOL, Video Search', 'internet', 'published', 'featured', 'admin', 0, 3),
(7, 'False Alarm: Why The Apple/Foxconn Debacle Clouds The Real Manufacturing Mess', '\r\n<p>I was walking home last week and the entire street Ã¢â‚¬â€œ and some of the sidewalk Ã¢â‚¬â€œ was blocked by large fire trucks and a gaggle of firemen in full regalia. The ladder truck was already planted firmly on the asphalt, ready to send a stream of water soaring over nearby apartment buildings and more trucks were coming, clogging the one-way street further.</p>\r\n<p>Convinced I was about to see an inferno, I tentatively crossed the street. I assumed IÃ¢â‚¬â„¢d be stopped and turned away. Instead, the firemen joked and jostled on the sidewalk and I saw a contractor arguing with someone I assumed to be a building resident. The contractor must have been welding Ã¢â‚¬â€œ you could still smell the flux and the smoke Ã¢â‚¬â€œ and the resident was clearly concerned.</p>\r\n<p>Ã¢â‚¬Å“I was working,Ã¢â‚¬Â yelled the contractor.</p>\r\n<p>Ã¢â‚¬Å“I was worried,Ã¢â‚¬Â said the resident.</p>\r\n<p>I bring this story up because that block is now faced with a dilemma. ItÃ¢â‚¬â„¢s good that the fire was averted by beagle-nosed residents and itÃ¢â‚¬â„¢s bad in that it tied up an entire fire department for an hour while a miscommunication was sorted out. The next time the resident wonÃ¢â‚¬â„¢t be so quick to phone the fire brigade and the contractor will do a better job of hiding the smoke.</p>\r\n<p>ThatÃ¢â‚¬â„¢s whatÃ¢â‚¬â„¢s about to happen at Foxconn.</p>\r\n<p><a href="http://www.apple.com/pr/library/2012/02/13Fair-Labor-Association-Begins-Inspections-of-Foxconn.html">Tim Cook just announced</a>&nbsp;that a blue ribbon panel of&nbsp;<a href="http://www.fairlabor.org/fla/">fair labor experts</a>&nbsp;will tour the grounds at Foxconn City where they will see&nbsp;all the horrors&nbsp;I saw a few months ago: a huge rice cooker big enough to feed 400,000 people, cramped but not squalid dorms, a handsome cybercafe with couplesÃ¢â‚¬â„¢ booths, and pools where exhausted line workers can enjoy a few laps before slumping into their loft beds.</p>\r\n<p>What they wonÃ¢â‚¬â„¢t see are disfigured workers toiling in Dickensian sweatshops, infants crawling through metal stamping machines, and workers chained to their stations until the millionth widget is shipped or they die of exhaustion. Why? Because Foxconn has been working with major manufacturers for long enough to know what they expect and theyÃ¢â‚¬â„¢ve seen enough European and American plants to know that squalid conditions beget squalid paychecks.</p>\r\n<p>The FLA, without a doubt, will return with a report citing a few underage workers, the recommendation to build bigger dorms, and an overall rating of, say B- in terms of safety and worker quality-of-life. ItÃ¢â‚¬â„¢s not perfect, theyÃ¢â‚¬â„¢ll say, but itÃ¢â‚¬â„¢s not horrible, especially when compared to garment shops.</p>\r\n<p>What will happen next? Apple will announce an all clear, the FLA will be less likely to attack Apple on rights violations, and Foxconn (and, more importantly, its competitors) will go back to business as usual. And weÃ¢â‚¬â„¢ll forget about this whole thing, our fingers worrying our Foxconn-made iPhones like a set of prayer beads.</p>\r\n<p>Then, quietly, the factories that are really running under horrible conditions, hiring workers without checking the particulars, and offering conditions that I wouldnÃ¢â‚¬â„¢t wish on any man, woman, or child, will go back to churning out smoke, albeit with a bit more secrecy. By focusing on the biggest Chinese (actually Taiwanese) manufacturer, we inspect the canopy of the tree while ignoring the disease-infested trunk.</p>\r\n<p>If you want to know what is happening in China,&nbsp;<a href="http://www.npr.org/blogs/money/2012/01/12/145038754/the-history-of-factory-jobs-in-america-in-one-town">listen to this</a>. It tells the story of a mill town South Carolina, Greenville, that has evolved, just as Foxconn is evolving. Back in its heyday the bars were buckets of blood, youngsters quit school at sixteen and clocked in for a great paycheck, and many lived far more comfortably than their agrarian fore-bearers. Now that mill town is shutting down, the last bar housing a pair of jokers who used to travel the world on a factory paycheck, and the real industry is down the road in clean, high-tech buildings where there are a hundred robots per human making widgets no 16-year-old drop-out could piece together, let alone understand.</p>\r\n<p>In the end, Greenville died and was reborn. So, too, will Shenzhen. The good companies in China, for years, supplied a better life for countless post-agrarian workers. They were not drafted into service. Instead, they walked up to the gates and applied for a job. Children were not pulled from their cribs to work in the darkness and noise, they were told theyÃ¢â‚¬â„¢d have a better life if they sat in chairs and assembled cellphones for fat Americans. This hand-waving by Apple wonÃ¢â‚¬â„¢t make the bad factories go away and it will encourage the good factories to automate much more. Why hire 400,000 complainers when you can hire 1,000 Chinese PhDs to run the line? Follow the arc of manufacturing in the US and youÃ¢â‚¬â„¢ll see the same arc repeated in Shenzhen.</p>\r\n<p>IÃ¢â‚¬â„¢m not here to defend Apple or Foxconn nor am I about to sing folksongs about the exploited, migrant electronics assemblers. This is about economics. ChinaÃ¢â‚¬â„¢s economy is booming, their unemployment rate was near 4.1% in 2010 (ours is 8.3% now and 9.1% in 2010), and what Shenzhen makes, the world takes. Apple doesnÃ¢â‚¬â„¢t like negative publicity, so theyÃ¢â‚¬â„¢re sending a third party to pick up some talking points and when that third party comes back, all smiles, weÃ¢â‚¬â„¢ll forget about real sweatshops in real places. Focusing on two huge companies in the pantheon of Asian manufacturing is like sending the entire firehouse after a little smoke. The real fires go right on blazing while the contractor gesticulates on the sidewalk, yelling Ã¢â‚¬Å“I was working. I was working.Ã¢â‚¬Â</p>\r\n', '6shutterstock_71112382.jpg', '13-02-2012', 'False Alarm: Why The Apple/Foxconn Debacle Clouds The Real Manufacturing Mess', 'False Alarm, Why The Apple,Foxconn Debacle ,Clouds The Real ,Manufacturing Mess', 'internet', 'published', 'normal', 'admin', 0, 0),
(8, 'Apple Beats Out Google, Amazon For Highest Corporate Reputation Score', '\r\n<p>Apple took the top spot from Google for the highest corporate reputation score, according to the&nbsp;<a href="http://www.prnewswire.com/news-releases/google-slips-into-second-as-apple-soars-to-coveted-top-spot-with-highest-reputation-score-in-history-according-to-13th-annual-harris-poll-rq-study-139203118.html#">2012 Harris Poll Reputation Quotient</a>, a survey which measures corporate reputation for brands and companies in the U.S. The poll asks the general public to measure the reputations of the 60 most visible companies in the country. As&nbsp;<a href="http://www.zdnet.com/blog/btl/google-takes-top-spot-in-reputation-rankings-apple-intel-amazon-close-behind/48092">reported last year</a>, Google was top of the list in 2011Ã¢â‚¬Â²s rankings, followed by Apple and Amazon.</p>\r\n<p>Harris says that Apple took the highest RQ score (85.62 this year, the highest RQ score ever achieved by any company in the 13 years of the RQ study) to secure the top spot in the ranking, with Google and the Coca-Cola Company taking the second and third spots, respectively. Amazon also saw a jump in reputation score, moving up from eighth to fourth place with Kraft Foods ranking fifth.</p>\r\n<p>In terms of year-over-year change, only Toyota, General Motors, BP, and Apple saw significant improvement in their RQ scores while one-quarter of companies saw drastic declines. Among those with the most significant declines, five were financial institutions, including Berkshire Hathaway. HP also saw a major decline and IBM and Intel, who both made the list last year, were absent this year.</p>\r\n<p><img src="http://tctechcrunch2011.files.wordpress.com/2012/02/google1.png?w=640" alt=""></p>\r\n<p>RQ actually measures six different factors in reputation that influence consumer behavior. Apple is top-ranked in four of the six dimensions of reputation, says Harris. These factors and the high scorers include Social Responsibility (Whole Foods); Emotional Appeal (Amazon.com), Financial Performance (Apple), Products &amp; Services (Apple), Vision &amp; Leadership (Apple) and Workplace Environment (Apple).</p>\r\n<p>Other interesting results from the survey include purchase and recommendation behavior from consumers. For example, in the future, Americans would Ã¢â‚¬Å“definitelyÃ¢â‚¬Â purchase products &amp; services from Amazon.com (71%), Kraft Foods (70%), and the Coca-Cola Company (64%). Americans would Ã¢â‚¬Å“definitelyÃ¢â‚¬Â invest in stock from Amazon.com (34%), Microsoft (23%), and the Coca-Cola Company (23%).</p>\r\n', '7google2.png', '13-02-2012', 'Apple Beats Out Google, Amazon For Highest Corporate Reputation Score', 'Apple Beats Out Google, Amazon For Highest ,Corporate ,Reputation Score', 'technology', 'published', 'normal', 'admin', 0, 4),
(9, 'Science-Backed Ã¢â‚¬ËœBirchbox For ChildrenÃ¢â‚¬â„¢s ClothesÃ¢â‚¬â„¢ Wittlebee Wants To Automate You', '<p>Because I am going to be an awesome Mom, I will totally keep my ears open to the best possible options for my kids, because IÃ¢â‚¬â„¢ve totally set my sights on that, focusing on family instead of career and all. Obviously.</p>\r\n<p>Until&nbsp;hell freezes over&nbsp;then, I will be buying my more reproduction-friendly friends subscriptions to<a href="http://www.wittlebee.com/">Wittlebee&nbsp;</a>(rhymes with Ã¢â‚¬Å“little beeÃ¢â‚¬Â), <a href="http://science-inc.com/">Science-</a>backed Ã¢â‚¬Å“get random stuff sent to youÃ¢â‚¬Â startup run by my old friend and former Myspace marketing exec&nbsp;<a href="http://www.crunchbase.com/person/sean-percival">Sean Percival.</a></p>\r\n<p>Wittlebee is like a&nbsp;<a href="https://www.birchbox.com/">Birchbox&nbsp;</a>for kids clothes, where parents can get recurring/one-time shipments of childrenÃ¢â‚¬â„¢s clothing for $39.99 a month Ã¢â‚¬â€ 8 items in total from brands like American Apparel and Cottonseed.</p>\r\n<p>And yes, you can pick gender, age style and color preferences in the on-boarding process.</p>\r\n<p>Percival tells me that the appeal of Wittlebee Ã¢â‚¬â€ in the same space as&nbsp;Tredup&nbsp;Ã¢â‚¬â€œ is essentially the money saved by new parents, as the startup essentially packs about $80 of clothing in to each $40 box of clothing sent.</p>\r\n<p>Ã¢â‚¬Å“Kids go through clothes fast and in todayÃ¢â‚¬â„¢s modern world itÃ¢â‚¬â„¢s especially hard to keep up,Ã¢â‚¬Â says Percival, Ã¢â‚¬Å“By auto shipping a few items each month, with sizes that grow along with your child, you can always stay one step ahead.Ã¢â‚¬Â</p>\r\n<p>A new father himself (thatÃ¢â‚¬â„¢s a picture of his daughter Charlotte below), he is sensitive to the needs of time-strapped parents, Ã¢â‚¬Å“The shopping experience with young kids can also be very challenging. With Wittlebee we save parents time, money and reduce those Ã¢â‚¬Ëœmall meltdownÃ¢â‚¬â„¢ moments.Ã¢â‚¬Â</p>\r\n<p>Knowing he wanted to do his own thing post Myspace, Percival tried out a few startup concepts before he settled on the Wittlebee model, operating the business out of an old server room at the Science offices until it grew enough to warrant a bigger office space.</p>\r\n<p><img img_6295="img_6295" src="http://tctechcrunch2011.files.wordpress.com/2012/02/img_6295.jpg?w=640" alt=""></p>\r\n<p>Percival was sure he wanted to do a subscription e-commerce play Ã¢â‚¬â€ and his decision payed off Ã¢â‚¬â€ at about 100 subscribers Wittlebee moved out of the server room and the company is now at about 500 ($20K in revenue) in a monthÃ¢â‚¬â„¢s time.</p>\r\n<p>Ã¢â‚¬Å“The business and much of what I do is inspired by [his wife]&nbsp;Laurie,Ã¢â‚¬Â says Percival, Ã¢â‚¬Å“After getting a bit disenchanted by social media and online marketing I could only focus on solving problems for the most important people out there, moms.Ã¢â‚¬Â</p>\r\n<p>His future plans include testing out additional verticals and expanding to other age groups and kid-friendly items like books. Ã¢â‚¬Å“In most cases IÃ¢â‚¬â„¢m trying to disrupt the existing and well established channels of retail so IÃ¢â‚¬â„¢ve gotten a few funny looks along the way,Ã¢â‚¬Â he says, Ã¢â‚¬Å“However clothing brands and some industry insiders have been genuinely excited about the model.Ã¢â‚¬Â</p>', '8wittlebee-logo-trans.png', '13-02-2012', 'Science-Backed Ã¢â‚¬ËœBirchbox For ChildrenÃ¢â‚¬â„¢s ClothesÃ¢â‚¬â„¢ Wittlebee Wants To Automate You', 'Science-Backe,Birchbox For ChildrenÃ¢â‚¬â„¢s, Clothes,Wittlebee Wants To Automate, Your KidÃ¢â‚¬â„¢s', 'news', 'published', 'normal', 'admin', 0, 0),
(10, 'Report: LinkedIn Leads In Social Job Recruiting Followed By Twitter And Facebook', '\r\n<p><a href="http://www.bullhorn.com/">Bullhorn</a>, which develops a recruitment software and applicant tracking system, is releasing a new&nbsp;<a href="http://www.bullhornreach.com/content/resources/reports">Social Recruiting Activity Report</a>, which examines the usage of social media in executive and job recruiting. Bullhorn found that LinkedIn leads among the frequency of usage by recruiters and their effectiveness for sourcing candidates, followed by Twitter, with Facebook coming in third.</p>\r\n<p>ItÃ¢â‚¬â„¢s important to note that this study examines activity by recruiters as opposed to actual job hunters. Despite the rise of Facebook&nbsp;as a source for job seeking and professional networking, BullhornÃ¢â‚¬â„¢s data shows that recruitersÃ¢â‚¬â„¢ LinkedIn networks still drive more views than their Twitter and Facebook networks combined. Recruiters who post jobs on social networks are likely to receive more applications from LinkedIn, with the social network driving almost nine times more applications than Facebook and three times more than Twitter.</p>\r\n<p>One interesting data point from the reportÃ¢â‚¬â€a Twitter follower is almost three times more likely to apply to a job than a LinkedIn connection, and more than eight times more likely to apply than a Facebook follower, indicating that Twitter might be a highly underutilized social recruiting channel. And Twitter followings drive almost twice as many job views per job as their Facebook fan bases.</p>\r\n<p>According to the report, 48 percent of recruiters use LinkedIn exclusively. These recruiters have an average of 661 connections, and donÃ¢â‚¬â„¢t leverage the other two networks for social recruiting. From there, recruiters use Twitter more than Facebook. Despite the fact that recruiters have fewer connections on Twitter (37 followers on average), 19 percent are connected to both LinkedIn and Twitter, while only 10 percent are connected to both LinkedIn and Facebook (245 friends on average).</p>\r\n<p>In terms of adoption, LinkedIn continues to grow at the fastest pace. The average recruiter adds 18.5 LinkedIn connections each week, compared to 3.3 Twitter followers, 1.4 Facebook friends. LinkedIn drives more views per job than Twitter and Facebook, generating three times the amount of views of Twitter and six times the amount of Facebook.</p>\r\n<p><img src="http://tctechcrunch2011.files.wordpress.com/2012/02/b1.png?w=640"></p>\r\n', '9b.png', '13-02-2012', 'Report: LinkedIn Leads In Social Job Recruiting Followed By Twitter And Facebook', 'Report: LinkedIn, Leads In Social Job, Recruiting Followed ,By Twitter And Facebook', 'news', 'published', 'normal', 'admin', 0, 1),
(11, 'Pops Raises $1.5M From Mangrove To Sexify Mobile Notifications', '<p><a href="http://getpo.ps/">Pops</a>, maker of an application that&nbsp;adds another dimension&nbsp;to the mobile notifications people receive on their Android smartphones, has raised $1.5 million from&nbsp;<a href="http://www.crunchbase.com/financial-organization/mangrove-capital-partners">Mangrove Capital Partners</a>, who were early investors in companies like Skype, Rdio, OpenX, Kupivip.ru, Nimbuzz and Wix.</p>\r\n<p>Pops basically enables mobile users to customize smartphone alerts, including new email, SMS, Facebook, Twitter, Google+ and whatnot in a more creative way than youÃ¢â‚¬â„¢re used to.</p>\r\n<p>PopsÃ¢â‚¬â„¢ technology essentially captures incoming notifications and personalizes them according to a userÃ¢â‚¬â„¢s needs and wishes, including animations or video clips that wrap the alerts in question.</p>\r\n<p>Not something for power users who receive such mobile notifications every other minute or worse, or rely on instant delivery of alerts for their professions or whatever, but itÃ¢â‚¬â„¢s nice to know you can at least customize those typically bland phone notifications.&nbsp;The startup behind the application plans to monetize the service by offering premium content. Since we last wrote about Pops, the app has garnered close to 500,000 downloads.</p>', '10pops2.png', '13-02-2012', 'Pops Raises $1.5M From Mangrove To Sexify Mobile Notifications', 'Pops Raises $1.5M, From Mangrove, To Sexify Mobile, Notifications', 'investment', 'published', 'normal', 'admin', 0, 0),
(12, 'Marin Software Raises $30M, Aims (Eventually) For IPO', '\r\n<p><a href="http://www.marinsoftware.com/">Marin Software</a>, a company that helps advertisers manage large online ad campaigns, has raised $30 million in a new round of funding.</p>\r\n<p>Founded in 2006, Marin initially offered tools to manage search advertising campaigns, but it has expanded into display, mobile, and social media, and the company now bills itself as Ã¢â‚¬Å“the worldÃ¢â‚¬â„¢s leading online advertising management platform.Ã¢â‚¬Â Marin says it doubled its customer base in the last year and now works with more than 1,500 advertisers and advertising agencies, who use the companyÃ¢â‚¬â„¢s services to manage $3.5 billion in annualized ad spending.</p>\r\n<p>The new round was led by Asian investment firm Temasek, with funding from SAP Ventures (which, like Temasek, is a new investor in Marin), Benchmark Capital, Crosslink Capital, DAG Ventures, and Triangle Peak Partners. Founder and CEO Chris Lien said the money will be spent on building the sales and customer support teams, and on product development. He also said this will probably be MarinÃ¢â‚¬â„¢s last round of private funding (it has&nbsp;<a href="http://techcrunch.com/2011/04/05/marin-software-raises-16-million-for-paid-search-management-platform/">raised a total of $80 million</a>): Ã¢â‚¬Å“At some point in the future, Marin expects to be a public company.Ã¢â‚¬Â</p>\r\n<p>When asked what 2012 will hold for the advertising industry at large, Lien suggested that the shifts underway in 2011 (and before) will continue.</p>\r\n<p>Ã¢â‚¬Å“In 2012, we expect to see ongoing migration of advertising dollars from offline media into online media as advertisers seek to have their marketing dollars follow audiences,Ã¢â‚¬Â he said. Ã¢â‚¬Å“2012 will see continued growth of all forms of online media highlighted by the growth of search, display, social, and mobile.Ã¢â‚¬Â</p>\r\n', '11amarin-software-ad-spend-under-management.jpg', '13-02-2012', 'Marin Software Raises $30M, Aims (Eventually) For IPO', 'Marin Software, Raises $30M, Aims (Eventually) For IPO', 'news', 'published', 'normal', 'admin', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `cateid` int(100) NOT NULL AUTO_INCREMENT,
  `catename` varchar(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`cateid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cateid`, `catename`, `selecttopic`) VALUES
(3, 'investment', 'blog'),
(4, 'news', 'blog'),
(7, 'internet', 'blog'),
(8, 'funny', 'videostream'),
(9, 'accident', 'videostream'),
(10, 'entertainment', 'videostream'),
(11, 'people', 'videostream'),
(12, 'animation', 'videostream'),
(13, '3d wallpapers', 'imagegallery'),
(14, 'game wallpapers', 'imagegallery'),
(15, 'world', 'imagegallery'),
(16, 'animals', 'imagegallery'),
(17, 'sea', 'imagegallery'),
(19, 'electronics', 'marketplace'),
(20, 'scripts', 'marketplace'),
(21, 'domains', 'marketplace'),
(26, 'jobs', 'adposting'),
(27, 'business', 'adposting'),
(28, 'dreamweaver', 'tutorials'),
(29, 'photoshop', 'tutorials'),
(30, 'electronics', 'productpublisher'),
(54, 'stones', 'edclub'),
(55, 'architecture', 'edclub'),
(57, 'websites', 'marketplace'),
(59, 'rent', 'realestate'),
(60, 'own', 'realestate'),
(61, 'MULTICMS', 'custom'),
(62, 'Other', 'custom');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `commentid` int(100) NOT NULL AUTO_INCREMENT,
  `comment` text,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  `id` int(100) DEFAULT NULL,
  PRIMARY KEY (`commentid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`commentid`, `comment`, `name`, `email`, `website`, `status`, `selecttopic`, `id`) VALUES
(2, 'This is second comment', 'ali', 'falconali2004@hotmail.com', 'http://sitetalk.com/rayice', 'published', 'edclub', 1),
(4, 'hahahaha', 'admin', 'trusthide@hotmail.com', 'masterboard.info', 'published', 'edclub', 2),
(9, 'xcvdfg', 'admin', 'trusthide@hotmail.com', 'http://www.unaico.in', 'published', 'videostream', 3),
(10, 'asdasdasd', 'admin', 'trusthide@hotmail.com', 'tophomesale.com', 'published', 'videostream', 3),
(11, 'sdadfsf', 'admin', 'trusthide@hotmail.com', 'Ali', 'published', 'imagegallery', 1),
(12, 'testedasdasdsa', 'admin', 'trusthide@hotmail.com', 'tophomesale.com', 'published', 'marketplace', 1),
(13, 'asdad', 'admin', 'trusthide@hotmail.com', 'tophomesale.com', 'pending', 'marketplace', 1),
(15, 'asdad', 'admin', 'trusthide@hotmail.com', 'http://wogor.com', 'published', 'realestate', 4);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `cid` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`cid`, `name`, `email`, `phone`, `subject`, `message`) VALUES
(2, 'Syed Raza', 'syedali_friend@yahoo.com', '+92-321-470-8525', 'This is my subject', 'Hello, Testing');

-- --------------------------------------------------------

--
-- Table structure for table `custom`
--

CREATE TABLE IF NOT EXISTS `custom` (
  `customid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `metadesc` varchar(100) NOT NULL,
  `metakey` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  PRIMARY KEY (`customid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `custom`
--

INSERT INTO `custom` (`customid`, `title`, `description`, `photo`, `dates`, `metadesc`, `metakey`, `catename`, `status`, `position`, `users`, `rating`, `views`) VALUES
(12, 'Marin Software Raises $30M, Aims (Eventually) For IPO', '<div class="oneone"><p><a href="http://www.marinsoftware.com/">Marin Software</a>, a company that helps advertisers manage large online ad campaigns, has raised $30 million in a new round of funding.</p>\r\n<p>Founded in 2006, Marin initially offered tools to manage search advertising campaigns, but it has expanded into display, mobile, and social media, and the company now bills itself as Ã¢â‚¬Å“the worldÃ¢â‚¬â„¢s leading online advertising management platform.Ã¢â‚¬Â Marin says it doubled its customer base in the last year and now works with more than 1,500 advertisers and advertising agencies, who use the companyÃ¢â‚¬â„¢s services to manage $3.5 billion in annualized ad spending.</p>\r\n<p>The new round was led by Asian investment firm Temasek, with funding from SAP Ventures (which, like Temasek, is a new investor in Marin), Benchmark Capital, Crosslink Capital, DAG Ventures, and Triangle Peak Partners. Founder and CEO Chris Lien said the money will be spent on building the sales and customer support teams, and on product development. He also said this will probably be MarinÃ¢â‚¬â„¢s last round of private funding (it has&nbsp;<a href="http://techcrunch.com/2011/04/05/marin-software-raises-16-million-for-paid-search-management-platform/">raised a total of $80 million</a>): Ã¢â‚¬Å“At some point in the future, Marin expects to be a public company.Ã¢â‚¬Â</p>\r\n<p>When asked what 2012 will hold for the advertising industry at large, Lien suggested that the shifts underway in 2011 (and before) will continue.</p>\r\n<p>Ã¢â‚¬Å“In 2012, we expect to see ongoing migration of advertising dollars from offline media into online media as advertisers seek to have their marketing dollars follow audiences,Ã¢â‚¬Â he said. Ã¢â‚¬Å“2012 will see continued growth of all forms of online media highlighted by the growth of search, display, social, and mobile.Ã¢â‚¬Â</p>\r\n</div>', '11amarin-software-ad-spend-under-management.jpg', '13-02-2012', 'Marin Software Raises $30M, Aims (Eventually) For IPO', 'Marin Software, Raises $30M, Aims (Eventually) For IPO', 'MULTICMS', 'published', 'normal', 'admin', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE IF NOT EXISTS `doctors` (
  `doctorsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`doctorsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctorsid`, `title`, `description`, `metadesc`, `metakey`, `author`, `dates`, `image`, `status`, `position`, `users`, `rating`, `views`, `selecttopic`) VALUES
(1, 'doctors system is ready', 'We have prepare the doctors system, and it is fully ready and active to work, we will figer our the way to make it more estable and professional for making it workable and structable to provide better solution of doctorsging. We are Working hard to make it more and more better, there are alot of diffrent ways to make it better, at first we will focus on making the theme of it after that we will focus to make its system professional and then so on.', NULL, NULL, 'Syed', '11-12-2012', NULL, 'published', 'normal', '', 0, 5, 'doctors'),
(5, 'Welcome To My Clinic', 'this is a doctor details.', 'this is detail of doctors', 'this is detail of doctors', 'admin', '1-1-2001', 'logo.png', 'published', 'featured', 'admin', 0, 0, 'homepage');

-- --------------------------------------------------------

--
-- Table structure for table `friendlinks`
--

CREATE TABLE IF NOT EXISTS `friendlinks` (
  `linkid` int(100) NOT NULL AUTO_INCREMENT,
  `linktitle` varchar(100) NOT NULL,
  `linkurl` varchar(100) NOT NULL,
  PRIMARY KEY (`linkid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `friendlinks`
--

INSERT INTO `friendlinks` (`linkid`, `linktitle`, `linkurl`) VALUES
(3, 'Rayice Network', 'http://www.rayice.com');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `imageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`imageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`imageid`, `title`, `description`, `imageurl`, `date`, `catename`, `status`, `rating`, `views`, `position`, `users`) VALUES
(1, 'Wallpaper with Great Sign', 'A beautiful wallpaper with great sign, this sign show the power of region, party or group.', '1.jpg', '12/12/2012', '3d wallpapers', 'published', 0, 5, 'featured', 'razashah'),
(5, 'The Edge Of The Earth Exists! ', '<a rel="3" style="display: block;" target="_blank" href="http://mgid.com/pnews/1125482/i/10442/pp/3/7/?k=faSMTMzMTcxNzU1MTcxODEwNDQyMzAxfbSMjAyfcSMTM2MTA4ZDM2NjY%3DfdSMTM2MTA4ZGVlNTY%3DfeSfgSMWY5fhSMjAwfiSMWE3fjSTVRNek1UY3hOekl6T1RRM01URXdORFF5TkRVPQ%3D%3DfkSMWVhflSfmSN2M%3DfnSMTg%3DfoSfpSMjAwfqSMjA%3DfrSMw%3D%3DfsSaHR0cDovL2ZsYXNoLXNjcmVlbi5jb20vZnJlZ%2413YWxscGFwZXIvc3VwZXJiLTNkLWRlc2lnbmVkLW5hdHVyZ%2413YWxscGFwZXJzLXRvLXNwaWNlLXlvdXItbGlmZ%2411cC8%3DftSaHR0cDovL2ZsYXNoLXNjcmVlbi5jb20vZnJlZ%2413YWxscGFwZXIvY2F0ZWdvcnksM2QuaHRtbA%3D%3DfuSc2VhcmNoLnlhaG9vLmNvbQ%3D%3DfvSNw%3D%3DfwSMWY5fxSMWEwYw%3D%3DfySMWE3fzSMTlmNg%3D%3D" class="mctitle10442">The Edge Of The Earth Exists! </a>', '41125482_b.jpg', '13-03-2012', '3d wallpapers', 'published', 0, 1, 'featured', 'admin'),
(6, '24 Delicious Car Wallpapers To Cover Your Desktop ', '<a rel="3" style="display: block;" target="_blank" href="http://mgid.com/pnews/1042660/i/10442/pp/5/7/?k=faSMTMzMTcxNzU1MTcxODEwNDQyMzAxfbSMjBlfcSMTM2MTA4ZDM2NjY%3DfdSMTM2MTA4ZWNkMTQ%3DfeSfgSMmI3fhSMjAzfiSMmI3fjSTVRNek1UY3hOekl6T1RRM01URXdORFF5TkRVPQ%3D%3DfkSMWVhflSfmSN2M%3DfnSMjQ%3DfoSfpSMjAzfqSMjA%3DfrSMw%3D%3DfsSaHR0cDovL2ZsYXNoLXNjcmVlbi5jb20vZnJlZ%2413YWxscGFwZXIvc3VwZXJiLTNkLWRlc2lnbmVkLW5hdHVyZ%2413YWxscGFwZXJzLXRvLXNwaWNlLXlvdXItbGlmZ%2411cC8%3DftSaHR0cDovL2ZsYXNoLXNjcmVlbi5jb20vZnJlZ%2413YWxscGFwZXIvY2F0ZWdvcnksM2QuaHRtbA%3D%3DfuSc2VhcmNoLnlhaG9vLmNvbQ%3D%3DfvSNw%3D%3DfwSMmI3fxSMWEwZg%3D%3DfySMmI3fzSMTlmNg%3D%3D" class="mctitle10442">24 Delicious Car Wallpapers To Cover Your Desktop </a>', '51042660_b.jpg', '13-03-2012', '3d wallpapers', 'published', 0, 0, 'normal', 'admin'),
(7, 'fire clean desktop backrounds moon vector wallpaper ', '<h2>fire clean desktop backrounds moon vector wallpaper </h2>', '6hd_wall_5291.jpg', '13-03-2012', '3d wallpapers', 'published', 0, 2, 'normal', 'admin'),
(8, 'background walpapers baby ', 'http://www.getfreehdwallpapers.com/wallpapers/background-walpapers-baby-183661.html', '7baby_16847.jpg', '13-03-2012', '3d wallpapers', 'published', 0, 1, 'normal', 'admin'),
(9, '3d Wallpapers', 'Animated Desktop Wallpaper', '8hd_wall_6912.jpg', '13-03-2012', '3d wallpapers', 'published', 0, 0, 'normal', 'admin'),
(24, 'Ice Sea', '<div class="oneone"><p>showing an Ice Sea</p></div>', '233.jpg', '13-03-2012', 'sea', 'published', 0, 2, 'normal', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `market`
--

CREATE TABLE IF NOT EXISTS `market` (
  `marketid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `imageurl1` varchar(100) DEFAULT NULL,
  `imageurl2` varchar(100) DEFAULT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `editorpick` varchar(100) NOT NULL,
  `current` varchar(100) NOT NULL,
  PRIMARY KEY (`marketid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `market`
--

INSERT INTO `market` (`marketid`, `title`, `description`, `imageurl`, `imageurl1`, `imageurl2`, `owner`, `date`, `price`, `catename`, `status`, `rating`, `views`, `position`, `users`, `editorpick`, `current`) VALUES
(1, 'westend.tv for sell', 'Hello, Westend.tv domain is for sell, anyone want to purchase it then contact me. My Number is 9123912931923', '16c57f58bad27b233b2382aa886a0-grande.jpg', 'logo.png', 'logo.png', 'razashah', '12/12/2012', '$1000', 'domains', 'published', 0, 15, 'featured', 'razashah', 'yes', 'open'),
(5, 'Domain For Sell', 'Domain is for sell', '10007053xmD7.jpg', NULL, NULL, 'admin', '12-12-2022', '$5000', 'electronics', 'published', 0, 8, 'featured', 'admin', 'yes', 'open'),
(6, 'Established Websites', '<div class="oneone"><p>www.tophomesale.com<br>www.shoperer.com<br>www.blogscompany.com<br>www.masterboard.info</p></div>', '1.jpg', NULL, NULL, 'admin', '13-03-2012', '500000', 'websites', 'published', 0, 4, 'normal', 'admin', 'yes', 'open');

-- --------------------------------------------------------

--
-- Table structure for table `marketmessage`
--

CREATE TABLE IF NOT EXISTS `marketmessage` (
  `marketmessageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `users` varchar(100) NOT NULL,
  `from` varchar(100) NOT NULL,
  `status1` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `marketid` int(100) NOT NULL,
  PRIMARY KEY (`marketmessageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `marketmessage`
--

INSERT INTO `marketmessage` (`marketmessageid`, `title`, `description`, `users`, `from`, `status1`, `status`, `marketid`) VALUES
(1, 'i want to buy it', 'Hello, i m interesting in your product!', 'admin', 'razashah', 'received', 'send', 2),
(2, 'I m Great', 'i m testing system', 'razashah', 'admin', 'received', 'send', 2),
(3, 'ExPayS Scammer', 'sdsadadads', 'admin', 'razashah', 'received', 'send', 2),
(4, 'thats good product, i m intrested', 'Hello, i like it please let me purchase it', 'admin', 'geniusfunds', 'received', 'send', 3),
(5, 'ok i m intrested to,', 'hello i want to sell it as well', 'geniusfunds', 'admin', 'received', 'send', 3),
(6, 'RAYICE CMS', 'adfdsfsf', 'razashah', 'admin', 'received', 'send', 1);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `imageurl` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `imageurl`) VALUES
(1, 'Photo0629.jpg'),
(2, '12.jpg'),
(3, '2000d053xmD7.jpg'),
(4, '340002053xmD7.jpg'),
(5, '41logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `memberid` int(100) NOT NULL AUTO_INCREMENT,
  `users` varchar(100) NOT NULL,
  `passs` varchar(100) NOT NULL,
  `level` varchar(15) DEFAULT NULL,
  `fullname` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `zip` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `yahooid` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `facebook` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`memberid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`memberid`, `users`, `passs`, `level`, `fullname`, `address`, `email`, `photo`, `zip`, `city`, `state`, `country`, `phone`, `yahooid`, `twitter`, `facebook`, `status`, `position`, `selecttopic`) VALUES
(1, 'syed', 'raza', 'member', 'Syed Member', 'Oslo City', 'ask@rayice.com', '1000b053xmD7.jpg', '00000', 'Oslo', 'Oslo', 'Norway', '004744444444', '', '', '', 'active', 'gold', NULL),
(5, 'syedraza', 'raza12', 'editor', 'Syed Editor', 'Oslo City', 'ask@rayice.com', '5favicon.png', '10250', 'Oslo', 'Oslo', 'Norway', '004744444444', NULL, NULL, NULL, 'active', 'gold', NULL),
(8, 'admin', '123456', 'administrator', 'Syed Admin', 'Oslo City', 'ask@rayice.com', 'missing.png', '10250', 'Oslo', 'Select a region', 'Norway', '004744444444', NULL, NULL, NULL, 'active', 'bronze', NULL),
(9, 'razashah', 'raza12', 'member', 'Syed Member', 'Oslo City', 'ask@rayice.com', NULL, '10250', 'Oslo', 'blue', 'Pakistan', '004744444444', NULL, NULL, NULL, 'pending', 'basic', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `newsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`newsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`newsid`, `title`, `description`, `selecttopic`, `views`) VALUES
(2, 'ExPayS Scammer', 'This is testing news', 'productpublisher', 11),
(3, 'MULTI CMS', '<font color="#424242" face="Arial"><span style="font-size: 12px;">MULTI CMSMULTI CMSMULTI CMS</span></font>', 'doctors', 10),
(4, 'Search Engine News', '<b>this</b><span style="font-weight: normal; "> is good <a href="http://google.com">search engine</a>, provides data information.</span>', 'searchengine', 8),
(5, 'Search Engine Systems', '<font color="#424242" face="Arial"><span style="font-size: 12px;">Search Engine Systems asd</span><br></font>', 'searchengine', 7),
(6, 'Website is launched', 'Website is launched and the content is appear here', 'marketplace', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `pageid` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `selecttopic` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageid`, `name`, `title`, `description`, `image`, `metadesc`, `metakey`, `selecttopic`, `position`, `views`) VALUES
(7, 'privacy', 'Privacy', 'Privacy Data Changed', 'Privacy', 'All about privacy of Multi CMS', 'privacy , detail, page', 'searchengine', 'bottommenu', 44),
(9, 'About', 'About US', 'about us', 'about us', 'about us', 'about us', 'searchengine', 'leftmenu', 38),
(10, 'Terms', 'Terms of Use', 'Terms of Use', 'Terms of Use', 'Terms of Use', 'Terms of Use', 'searchengine', 'bottommenu', 47),
(11, 'Help', 'Help & Support', 'Help & Support', 'Help & Support', 'Help & Support', 'Help & Support', 'portfolio', 'menu', 45);

-- --------------------------------------------------------

--
-- Table structure for table `parts`
--

CREATE TABLE IF NOT EXISTS `parts` (
  `partsid` int(100) NOT NULL AUTO_INCREMENT,
  `part` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`partsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `parts`
--

INSERT INTO `parts` (`partsid`, `part`, `status`, `type`) VALUES
(1, 'news portal', 'active', 'component'),
(2, 'contact system', 'active', 'component'),
(3, 'ads management', 'disabled', 'component'),
(4, 'content pages', 'active', 'component'),
(5, 'external links', 'active', 'component'),
(6, 'top menu', 'active', 'module'),
(7, 'left menu', 'active', 'module'),
(8, 'right menu', 'disabled', 'module'),
(9, 'footer menu', 'active', 'module'),
(11, 'categories', 'active', 'component'),
(12, 'widgets', 'disabled', 'component'),
(13, 'sidecategories', 'active', 'module'),
(14, 'topcategories', 'active', 'module'),
(15, 'left ads', 'active', 'module'),
(16, 'right ads', 'active', 'module');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
  `portfolioid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `metadesc` varchar(160) DEFAULT NULL,
  `metakey` varchar(250) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `dates` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `rating` int(100) NOT NULL,
  `views` int(100) NOT NULL,
  `selecttopic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`portfolioid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`portfolioid`, `title`, `description`, `metadesc`, `metakey`, `author`, `dates`, `image`, `status`, `position`, `users`, `rating`, `views`, `selecttopic`) VALUES
(6, 'Researh Paper', 'Description about research ', NULL, NULL, 'admin', '12-12-2012', 'logo.png', 'published', 'featured', 'admin', 0, 10, 'portfolio'),
(8, 'Welcome To Portfolio Site', 'This is my personal portfolio website.', NULL, NULL, 'admin', '28-01-2012', 'logo.png', 'published', 'featured', 'admin', 0, 50, 'homepage');

-- --------------------------------------------------------

--
-- Table structure for table `productpublisher`
--

CREATE TABLE IF NOT EXISTS `productpublisher` (
  `productpublisherid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `productpublisherurl` varchar(100) DEFAULT NULL,
  `owner` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`productpublisherid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `productpublisher`
--

INSERT INTO `productpublisher` (`productpublisherid`, `title`, `description`, `imageurl`, `productpublisherurl`, `owner`, `date`, `catename`, `status`, `rating`, `views`, `position`, `editorpick`, `users`) VALUES
(1, 'IPHONE 42', 'a brand new iphone 4 model, get it and enjoy.', '14.jpg', 'http://www.apple.com/?ref=syed', 'syed', '12/12/2012', 'electronics', 'published', 5, 27, 'normal', 'yes', 'razashah');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `reportid` int(11) NOT NULL AUTO_INCREMENT,
  `users` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `date` varchar(20) DEFAULT NULL,
  `catename` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`reportid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`reportid`, `users`, `title`, `date`, `catename`) VALUES
(1, 'admin', 'adposting', '18-05-2012', 'jobs');

-- --------------------------------------------------------

--
-- Table structure for table `searchengine`
--

CREATE TABLE IF NOT EXISTS `searchengine` (
  `searchengineid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) DEFAULT NULL,
  `siteurl` varchar(100) NOT NULL,
  `dates` varchar(100) DEFAULT NULL,
  `metadesc` varchar(100) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  PRIMARY KEY (`searchengineid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `searchengine`
--

INSERT INTO `searchengine` (`searchengineid`, `title`, `siteurl`, `dates`, `metadesc`, `metakey`, `status`, `position`, `views`) VALUES
(5, 'RAYICE CMS - New Style Creativity / New Style CMS', 'www.rayice.com', '11-12-2012', 'RAYICE CMS - New Style Creativity / New Style CMS - Downlod Now and make Your Website without any Co', 'rayice cms, ray cms, new style cms, creativity cms, new features cms, content management system', 'published', NULL, 123),
(6, 'Amazing Accident', 'www.google.com', '11-13-2012', 'about us', 'New Mutli, Technology is Prepared', 'published', NULL, 444);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `settingid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `siteurl` varchar(100) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `favicon` varchar(100) DEFAULT NULL,
  `selecttopic` varchar(100) NOT NULL,
  `installed` varchar(100) NOT NULL,
  `currency` varchar(100) NOT NULL,
  `theme` varchar(100) NOT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `missingimage` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `metadesc` varchar(160) DEFAULT NULL,
  `metakey` varchar(100) DEFAULT NULL,
  `onlinestatus` varchar(100) NOT NULL,
  `footer` text NOT NULL,
  `host` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `database` varchar(100) DEFAULT NULL,
  `ad1` text,
  `ad2` text,
  `ad3` text,
  `ad4` text,
  `ad5` text,
  `ad6` text,
  PRIMARY KEY (`settingid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingid`, `title`, `siteurl`, `logo`, `favicon`, `selecttopic`, `installed`, `currency`, `theme`, `owner`, `missingimage`, `email`, `phone`, `metadesc`, `metakey`, `onlinestatus`, `footer`, `host`, `username`, `password`, `database`, `ad1`, `ad2`, `ad3`, `ad4`, `ad5`, `ad6`) VALUES
(1, 'MULTI CMS', 'http://demo.rayice.com/', '11logo.png', 'favicon.png', 'custom', 'no', 'USD', 'default', 'Developer', 'missing.png', 'ask@rayice.com', '0047444444444', 'RAYICE CMS - New Style Creativity / New Style CMS - Downlod Now and make Your Website without any Coding Knowledge.', 'rayice cms, ray cms, new style cms, creativity cms, new features cms, content management system', 'yes', '<div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><div class="oneone"><p>(c) copyright 2012 RAYICE Multi Content Management System. All Rights Reserved.</p></div></div></div></div></div>', 'localhost', 'root', NULL, 'rayicecms', '<script type="text/javascript"><!--\r\ngoogle_ad_client = "ca-pub-xxxxxxxxxxxxxxxxxxx";\r\n/* 300x250, created 2/17/10 */\r\ngoogle_ad_slot = "4924434531";\r\ngoogle_ad_width = 300;\r\ngoogle_ad_height = 250;\r\n//-->\r\n</script>\r\n<script type="text/javascript"\r\nsrc="http://pagead2.googlesyndication.com/pagead/show_ads.js">\r\n</script>', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `themeid` int(100) NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`themeid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`themeid`, `theme`, `status`) VALUES
(1, 'default', 'active'),
(2, 'html5', 'disabled');

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE IF NOT EXISTS `tutorials` (
  `tutorialsid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `owner` varchar(100) DEFAULT NULL,
  `date` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `tutorialsurl` varchar(100) NOT NULL,
  `tutorialpathurl` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  `editorpick` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`tutorialsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tutorials`
--

INSERT INTO `tutorials` (`tutorialsid`, `title`, `description`, `imageurl`, `owner`, `date`, `catename`, `tutorialsurl`, `tutorialpathurl`, `status`, `rating`, `views`, `position`, `users`, `editorpick`) VALUES
(6, 'Building Website In 5 Steps', '<div class="oneone"><p>sdfsdf</p></div>', '1.jpg', 'admin', '09-12-2011', 'dreamweaver', 'www.pixel2life.com', 'http://givesystem.com/testing', 'published', 0, 11, 'featured', 'admin', 'no');

-- --------------------------------------------------------

--
-- Table structure for table `tutorialsmessage`
--

CREATE TABLE IF NOT EXISTS `tutorialsmessage` (
  `tutorialsmessageid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `users` varchar(100) NOT NULL,
  `from` varchar(100) NOT NULL,
  `status1` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `tutorialsid` int(100) NOT NULL,
  PRIMARY KEY (`tutorialsmessageid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tutorialsmessage`
--

INSERT INTO `tutorialsmessage` (`tutorialsmessageid`, `title`, `description`, `users`, `from`, `status1`, `status`, `tutorialsid`) VALUES
(1, 'i want to buy it', 'Hello, i m interesting in your product!', 'admin', 'razashah', 'received', 'send', 2),
(2, 'I m Great', 'i m testing system', 'razashah', 'admin', 'received', 'send', 2),
(3, 'ExPayS Scammer', 'sdsadadads', 'admin', 'razashah', 'received', 'send', 2),
(4, 'thats good product, i m intrested', 'Hello, i like it please let me purchase it', 'admin', 'geniusfunds', 'received', 'send', 3),
(5, 'ok i m intrested to,', 'hello i want to sell it as well', 'geniusfunds', 'admin', 'received', 'send', 3);

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `videoid` int(100) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `videourl` varchar(100) DEFAULT NULL,
  `embedcode` text,
  `date` varchar(100) NOT NULL,
  `videotype` varchar(100) NOT NULL,
  `catename` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `rating` int(100) DEFAULT NULL,
  `views` int(100) DEFAULT NULL,
  `position` varchar(100) NOT NULL,
  `users` varchar(100) NOT NULL,
  PRIMARY KEY (`videoid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`videoid`, `title`, `description`, `imageurl`, `videourl`, `embedcode`, `date`, `videotype`, `catename`, `status`, `rating`, `views`, `position`, `users`) VALUES
(7, 'THE 99 Animation Preview', '<div class="oneone"><p>Ninety-nine mystical Noor Stones carry all that is left of the wisdom and knowledge of the lost civilization of Baghdad. The Noor Stones lie scattered across the globe-now little more than a legend. However one man has made it his life''s mission to seek out what was once lost. His name is Dr. Ramzi Razem and he has searched long and hard for the missing stones, to no avail. His luck is about to change...</p></div>', '0[1].jpg', '0[1].jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/SiYU3DZCepQ" frameborder="0" allowfullscreen></iframe>', '13-02-2012', 'embed', 'animation', 'published', 0, 1, 'featured', 'admin'),
(8, 'EXACTLY how a car engine works - 3D animation !', '<div class="oneone"><p>Check this out it is so cool and exactly how an internal combustion engine works ! WOW</p></div>', '70[1].jpg', '70[1].jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/FfTX88Sv4I8" frameborder="0" allowfullscreen></iframe>', '13-02-2012', 'embed', 'animation', 'published', 0, 1, 'featured', 'admin'),
(9, 'Round 6 - Short 3D Animated Film | HD', '<div class="oneone"><p>Round 6 is a short 3D animated film created by Snowball Studios.\r\nOur goal was to make a high quality video game cinematic styled trailer. In order to do this, we invented a video game called Fragball on which we based the action. The film shows an excerpt from the personal story of Snap, a Fragball gamer, as he plays out his last round - round 6.</p></div>', '80[1].jpg', '80[1].jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/nRPqVDboQDw" frameborder="0" allowfullscreen></iframe>', '13-02-2012', 'embed', 'animation', 'published', 0, 2, 'normal', 'admin'),
(10, '3d animation short "Bath Time"', 'A funny animated short that I made some time a go, hope you enjoy it.', '90[1].jpg', '80[1].jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/qGI_cVCmSaE" frameborder="0" allowfullscreen></iframe>', '13-02-2012', 'embed', 'animation', 'published', 0, 3, 'normal', 'admin'),
(11, 'ERWÃ‚Â© 3D Animation', 'The ERWÃ‚Â© is more than simply a re-design of the wheels and tires we have been using for over a hundred years. Rather, it is a complete re-imagining of what a wheel could, should be. By starting from scratch we were able to discard any preconceptions and create a wheel that is safer, more efficient and still maintains an incredible sense of style. We can bolt this innovation onto any vehicle on the road today and instantly make the world a greener place to live!', '100[3].jpg', '80[1].jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/OGvRnij2zoE" frameborder="0" allowfullscreen></iframe>', '13-02-2012', 'embed', 'animation', 'published', 0, 10, 'featured', 'admin'),
(12, 'Best Funny Video Ever', '<div class="oneone"><div class="oneone"><p>Best Funny Video Ever</p></div></div>', '11best_funny.jpg', '11best_funny.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/mIMFL9wRaJE" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 3, 'featured', 'admin'),
(13, 'The world''s most funny dog video', '<div class="oneone"><p>The world''s most funny dog video</p></div>', '12default.jpg', '12default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/Hb92wQpPG-s" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 2, 'normal', 'admin'),
(14, 'Funny Video clips mix - Greatest Youtube video''s EVER!!! ', '<h1 id="watch-headline-title">\r\n      \r\n\r\n\r\n  <span id="eow-title" class="long-title" dir="ltr" title="Funny Video clips mix - Greatest Youtube video''s EVER!!!">\r\n    Funny Video clips mix - Greatest Youtube video''s EVER!!!\r\n  </span>\r\n\r\n    </h1>', '13default.jpg', '12default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/c7ct6pNOvEE" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 1, 'normal', 'admin'),
(15, 'Best of just for laughs 2011', '<h1 id="watch-headline-title"><span id="eow-title" class="" dir="ltr" title="Best of just for laughs  2011  part 8">Best of just for laughs\r\n  </span></h1>', '14default.jpg', '12default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/ViKD9Sfsv9g" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 1, 'featured', 'admin'),
(16, 'monkey vs. dog', '<div class="oneone"><p>IÃ¯Â»Â¿ wish humans would love each other like this.</p></div>', '15default1.jpg', '15default1.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/OVEeaWPmOp4" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 0, 'normal', 'admin'),
(17, 'Monkey Vs Dog', '<h1 id="watch-headline-title"><span id="eow-title" class="" dir="ltr" title="Monkey Vs Dog....you know who wins!!!!!">You know who wins!!!!!</span></h1>', '16default.jpg', '15default1.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/ogIJSNVjJP8" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'funny', 'published', 0, 2, 'featured', 'admin'),
(18, 'Battle at Kruger', '<div class="oneone"><p></p><h1 id="watch-headline-title"><span id="eow-title" class="" dir="ltr" title="Battle at Kruger">Battle at Kruger\r\n  </span></h1><p></p></div>', '17default.jpg', '17default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/LU8DDYz68kM" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 2, 'featured', 'admin'),
(19, 'Mixed Car crashes, accidents and sport accidents - FUNNY ', 'A bunch of dumbasses.... o.o were talking crashes, accidents, injuries, car crashes, mistakes and just plaine stupidity.', '18default.jpg', '17default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/LDc4xzK9esA" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(20, 'Gymnastics Fail Blog', '<h1 id="watch-headline-title"><span id="eow-title" class="long-title" dir="ltr" title="Gymnastics Fail Blog- funny flip bloopers gone wrong accidents">Gymnastics Fail Blog- funny flip bloopers gone wrong accidents\r\n</span></h1>', '19default.jpg', '17default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/5IP7STVCN2U" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(21, 'Train Crash', 'This tittle is aÃ£â‚¬â‚¬joke. This was taken in Japan.', '20default.jpg', '17default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/Niiyh3sxwYk" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(22, 'The most dangerous place on Earth', 'Take a closer look at Liberia, one of the most dangerous places in the \r\nworld, with BBC travel documentary, Holidays in the Danger Zone - \r\nViolent Coast.', '21default1.jpg', '17default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/52166D7Hh8U" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(23, 'TRUCK VS BIG TRAIN', 'NO INJURIES REPORTED. will the truck make it across???', '22default.jpg', '17default.jpg', '<iframe width="420" height="315" src="http://www.youtube.com/embed/qGacupc5-tE" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(24, 'Extreme Crash Compilation ', '<div class="oneone"><p></p><h1 id="watch-headline-title"><span id="eow-title" class="" dir="ltr" title="Extreme Crash Compilation">Extreme Crash Compilation\r\n  </span></h1><p></p></div>', '23default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/PaBqiHALp7E" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'published', 0, 0, 'normal', 'admin'),
(25, 'Extreme Pogo Accident - HD Faceplant (ORIGINAL VIDEO) ', 'One of the drivers from the Drift Effect demo hosted by Drift Cleveland \r\ndecided to do some pogo''ing and put a big hole in his lip! But on the \r\nbright side he did', '24default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/7zidj-Q4UoE" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'accident', 'pending', 0, 0, 'normal', 'admin'),
(26, 'EXO-M_HISTORY_Music Video (Chinese ver.) ', '<div class="oneone"><p>The world of new boy band EXO-K and EXO-M presented by </p></div>', '25default.jpg', '25default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/i1xFTx8alMU" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'featured', 'admin'),
(27, 'EXO Teaser 21_KAI (7) ', 'EXO-K and EXO-M are new boy groups who will lead the world music \r\nindustry from now on! They are making their debut on the same day and \r\ntime with the same song in Korea and China. Since it''s something that \r\nhas never been tried before, it will be good enough to capture the \r\nattention of fans not only in Korea and China but all over the world.', '26default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/sQyjKBz0tG8" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(28, 'Vanessa Hudgens Say Ok Music Video (Official with Zac Efron) ', 'Say Ok is the second single from High School Musical''s own Vanessa \r\nHudgens.  Her debut album V is in stores now and features the hit single\r\n "Come Back To Me".', '27default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/F5VvvVxuKko" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(29, 'Ghost Rider 2 Movie Trailer Analysis', 'Spirit of Vengeance directors Neveldine &amp; Taylor provide commentary \r\nfor the newest Ghost Rider 2 movie trailer in this IGN Rewind Theater.', '28default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/e0LEQpVq-tk" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(30, 'Prototype 2 | Reveal Trailer [HD] ', 'Prototype 2 | Reveal Trailer [HD] ', '29default.jpg', '23default.jpg', '<iframe width="560" height="315" src="http://www.youtube.com/embed/6JZsWgei9xI" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(31, 'Mano Nethram', 'It is a fiction short film. In this story the hero is an architectural \r\nengineer. He visits his new project site to design the ''River Resort''. \r\nHe happens to see a ''Saint'' in his work and gets realization. In this \r\nstory, the hero tries to bring about change in society by eradicating \r\ncorruption. This story has been inspired by an interview of Sri A.P.J. \r\nAbdul Kalam on television.', '30default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/lQdmbPpSVW8" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(32, 'John Carter - Cast & Director Interview ', 'IGN chats with director Andrew Stanton and the stars of the new epic \r\nmovie, including John Carter himself, Taylor Kitsch, about why John \r\nCarter and his saga are important to science fiction.', '31default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/X_dUh3z4GR4" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'entertainment', 'published', 0, 0, 'normal', 'admin'),
(33, 'Cloning People and Pets- Beyond Invention ', '<h1 id="watch-headline-title">The latest in cloning technolgy- pets, people<span id="eow-title" class="" dir="ltr" title="Cloning People and Pets- Beyond Invention"> - Beyond Invention.</span></h1>', '32default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/p-L1ogDuMGA" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'featured', 'admin'),
(34, 'Why Are People Different?: Differences ', 'Why are people different from one another? This lecture addresses this \r\nquestion by reviewing the latest theories and research in psychology on \r\ntwo traits in particular: personality and intelligence. Students will \r\nhear about how these traits are measured, why they may differ across \r\nindividuals and groups, and whether they are influenced at all by one''s \r\ngenes, parents or environment.', '33default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/piDznzrNymE" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(35, 'Garza Twins Interrupted Therapy Session on "Head Case"', 'A sneaky plan and a special actor help Dr. Goode''s ex sneak back into her life on the Starz Original "Head Case."', '34default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/vr3k39RJum4" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(36, 'Double Trouble Twins', '<h1 id="watch-headline-title"><span id="eow-title" class="" dir="ltr" title="Double Trouble Twins">Double Trouble Twins</span></h1>', '35default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/dxeuJmsXDEo" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(37, 'FIGHT!!! Very Funny, TV Confrontations w/ angry Celebrities ', 'This clip contains violent celebrities/well known people getting pissed \r\noff at each other in front of Television Cameras...It is a long clip, \r\nand may take long to download,but it is worth it and very entertaining \r\nto see them lose their composure.  ', '36default.jpg', '23default.jpg', '<iframe width="420" height="315" src="http://www.youtube.com/embed/mfunzu0-SAY" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(38, 'Soulful Dynamics - Jungle People ', 'A tribute video I made for Soulful Dynamics, and their song "Jungle People" released in 1976', '37default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/IitEzWaeBj4" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(39, 'Jim Jones feat. Trav, Mel Matrix & Shoota - Cold (Official Video) ', '<h1 id="watch-headline-title">\r\n      \r\n\r\n\r\n  <span id="eow-title" class="long-title" dir="ltr" title="Jim Jones feat. Trav, Mel Matrix &amp; Shoota - Cold (Official Video)">\r\n    Jim Jones feat. Trav, Mel Matrix &amp; Shoota - Cold (Official Video)\r\n  </span>\r\n\r\n    </h1>', '38default.jpg', '23default.jpg', '<iframe width="560" height="315" src="http://www.youtube.com/embed/gwp476ieRqk" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'people', 'published', 0, 0, 'normal', 'admin'),
(40, 'Marvel Avengers Assemble (2012) Watch the Official trailer | HD ', 'Marvel''s ultimate team of heroes, the Avengers, storm into UK cinemas on 26th April in ''Marvel Avengers Assemble''.', '39default.jpg', '23default.jpg', '<iframe width="560" height="315" src="http://www.youtube.com/embed/NPoHPNeU9fc" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'animation', 'published', 0, 0, 'normal', 'admin'),
(41, 'Pedestrian Revenge ', 'What happens when a npc straps on a pair of lollerblades and brings the fight to YOU?', '40default.jpg', '23default.jpg', '<iframe width="100%" height="515" src="http://www.youtube.com/embed/xlEcTkMHbA4" frameborder="0" allowfullscreen></iframe>', '13-03-2012', 'embed', 'animation', 'published', 0, 4, 'featured', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `widgets`
--

CREATE TABLE IF NOT EXISTS `widgets` (
  `wid` int(100) NOT NULL AUTO_INCREMENT,
  `widget` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `widgets`
--

INSERT INTO `widgets` (`wid`, `widget`, `content`, `position`, `status`) VALUES
(1, 'google', 'Add Google Widget To Multi CMS', 'contact', 'active'),
(2, 'weather', 'weather code', 'contact', 'acitve');
