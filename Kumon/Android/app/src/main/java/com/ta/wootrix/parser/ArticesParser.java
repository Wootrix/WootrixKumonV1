package com.ta.wootrix.parser;

import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class ArticesParser implements Parser<IModel> {

    int articleType;
    private boolean isOpenArticle;

    // ==0 for articless in the mobile
    // ==1 for articless in the tablet
    // ==2 for articles search

    /**
     * whether the object is made for openarticle or magazine article
     *
     * @param isOpenArticle -- true for open articles
     */
    public ArticesParser(boolean isOpenArticle, int articleType) {
        this.isOpenArticle = isOpenArticle;
        this.articleType = articleType;
    }

    // public ArticesParser(int articleType)
    // {
    // this
    // }

    @Override
    public IModel parse(JSONObject json) throws JSONException {
        return null;
    }

    @Override
    public Collection<IModel> parse(JSONArray array) throws JSONException {
        return null;
    }

    @Override
    public ArrayList<IModel> parse(String resp) throws JSONException {
        ArrayList<IModel> articlseList = new ArrayList<IModel>();
        JSONObject obj = new JSONObject(resp);
        if (obj.getBoolean("success") && obj.getJSONArray("data").length() > 0) {
            JSONObject mainTopicsJson = obj.getJSONArray("data").getJSONObject(0);
            String coverImage = "";
            JSONArray topicArray = mainTopicsJson.optJSONArray(isOpenArticle ? "openArticles" : "magazineArticles");
            if (topicArray != null && topicArray.length() > 0) {
                for (int i = 0; i < topicArray.length(); i++) {
                    // "articleId": "",
                    // "articleType": "text/video",
                    // "title": "",
                    // "createdDate": "",
                    // "source": "",
                    // "fullSource": "",
                    // "articleDescPlain": "",
                    // "articleDescHTML": "",
                    // "coverPhotoUrl": "",
                    // "articleVideoUrl": "",
                    // "allowShare": "",
                    // "allowComment": "",
                    // "commentsCount": "",
                    // "detailScreen": "Y/N"

                    JSONObject openArticleJson = topicArray.getJSONObject(i);
                    ArticleModle modle = new ArticleModle();
                    modle.setArticleID(openArticleJson.optString("articleId"));
                    modle.setArticleType(openArticleJson.optString("coverPhotoUrl").equalsIgnoreCase("") ? "text" : openArticleJson.optString("articleType"));
                    modle.setTitle(openArticleJson.optString("title"));
                    modle.setCreateDate(openArticleJson.optString("createdDate"));
                    modle.setSource(openArticleJson.optString("source"));
//                    if (openArticleJson.optString("fullSoruce").contains(".pdf")) {
//                        modle.setFullSource("http://drive.google.com/viewerng/viewer?embedded=true&url=" + openArticleJson.optString("fullSoruce"));
//                    } else {
                    modle.setFullSource(openArticleJson.optString("fullSoruce"));
                    //   }
                    // String
                    // articalPlanedesc=Utility.changeSpecialCharectorIssue(openArticleJson.optString("articleDescPlain"));
                    if (articleType == 0)
                        modle.setArticleDescPlain(openArticleJson.optString("articleDescPlain").length() > 250 ? openArticleJson.optString("articleDescPlain").substring(0, 249)
                                : openArticleJson.optString("articleDescPlain"));
                    else if (articleType == 1)
                        modle.setArticleDescPlain(openArticleJson.optString("articleDescPlain").length() > 650 ? openArticleJson.optString("articleDescPlain").substring(0, 649)
                                : openArticleJson.optString("articleDescPlain"));
                    else if (articleType == 2)
                        modle.setArticleDescPlain(openArticleJson.optString("articleDescPlain").length() > 30 ? openArticleJson.optString("articleDescPlain").substring(0, 29)
                                : openArticleJson.optString("articleDescPlain"));
                    modle.setArticleDescHtml(openArticleJson.optString("articleDescHTML"));
                    modle.setCoverPhotoUrl(openArticleJson.optString("coverPhotoUrl"));
                    modle.setVideoURL(openArticleJson.optString("articleVideoUrl"));
                    modle.setCanShare(openArticleJson.optString("allowShare").equalsIgnoreCase("Y") ? true : false);
                    modle.setCanComment(openArticleJson.optString("allowComment").equalsIgnoreCase("Y") ? true : false);

                    modle.setCommentCount(openArticleJson.optString("commentsCount"));
                    modle.setCreatedSource(openArticleJson.optString("createdSource"));

					/* changed by vipin for show the detail of artical by htlm data */
                    /*
                     * modle.setDetailLoadFromHtmlData(openArticleJson.optString(
					 * "createdBy").equalsIgnoreCase("1") ? true : false);
					 */
                    modle.setDetailLoadFromHtmlData(openArticleJson.optString("createdBy").equalsIgnoreCase("0") ? false : true);
                    modle.setCanSeeDetail(openArticleJson.optString("detailScreen").equalsIgnoreCase("Y") ? true : false);
                    modle.setTotalPages(mainTopicsJson.optInt("totalPages"));
                    if (!isOpenArticle) {
                        // add key for cover page
                        coverImage = mainTopicsJson.optString("cover_image");
                        modle.setCoverImageMag(coverImage);
                    }

					/* for embedded video */
                    modle.setEmbeddedThumb(openArticleJson.optString("embedded_thumbnail"));
                    modle.setEmbeddedVideoUrl(openArticleJson.optString("embedded_video_link"));

                    articlseList.add(modle);

                }
            }
            return articlseList;
        } else {
            com.ta.wootrix.modle.Error error = new com.ta.wootrix.modle.Error();
            error.setError(obj.getString("message"));
            articlseList.add(error);
            return articlseList;
        }
    }
}
