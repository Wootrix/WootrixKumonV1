package com.ta.wootrix.parser;

import com.ta.wootrix.modle.ArticleModle;
import com.ta.wootrix.modle.IModel;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collection;

public class ArticleParser implements Parser<IModel> {

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
    public ArticleParser(boolean isOpenArticle, int articleType) {
        this.isOpenArticle = isOpenArticle;
        this.articleType = articleType;
    }

    // public ArticesParser(int articleType)
    // {
    // this
    // }

    @Override
    public IModel parse(JSONObject obj) throws JSONException {

        ArticleModle modle = new ArticleModle();

        if (obj.getBoolean("success") && obj.getJSONObject("data").length() > 0) {

            JSONObject articleJson = obj.getJSONObject("data").getJSONObject("openArticles");

            if (articleJson != null && articleJson.length() > 0) {

                modle.setArticleID(articleJson.optString("articleId"));
                modle.setArticleType(articleJson.optString("coverPhotoUrl").equalsIgnoreCase("") ? "text" : articleJson.optString("articleType"));
                modle.setTitle(articleJson.optString("title"));
                modle.setCreateDate(articleJson.optString("createdDate"));
                modle.setSource(articleJson.optString("source"));
                modle.setFullSource(articleJson.optString("fullSoruce"));

                if (articleType == 0)
                    modle.setArticleDescPlain(articleJson.optString("articleDescPlain").length() > 250 ? articleJson.optString("articleDescPlain").substring(0, 249)
                            : articleJson.optString("articleDescPlain"));
                else if (articleType == 1)
                    modle.setArticleDescPlain(articleJson.optString("articleDescPlain").length() > 650 ? articleJson.optString("articleDescPlain").substring(0, 649)
                            : articleJson.optString("articleDescPlain"));
                else if (articleType == 2)
                    modle.setArticleDescPlain(articleJson.optString("articleDescPlain").length() > 30 ? articleJson.optString("articleDescPlain").substring(0, 29)
                            : articleJson.optString("articleDescPlain"));
                modle.setArticleDescHtml(articleJson.optString("articleDescHTML"));
                modle.setCoverPhotoUrl(articleJson.optString("coverPhotoUrl"));
                modle.setVideoURL(articleJson.optString("articleVideoUrl"));
                modle.setCanShare(articleJson.optString("allowShare").equalsIgnoreCase("Y") ? true : false);
                modle.setCanComment(articleJson.optString("allowComment").equalsIgnoreCase("Y") ? true : false);

                modle.setCommentCount(articleJson.optString("commentsCount"));
                modle.setCreatedSource(articleJson.optString("createdSource"));

                modle.setDetailLoadFromHtmlData(articleJson.optString("createdBy").equalsIgnoreCase("0") ? false : true);
                modle.setCanSeeDetail(articleJson.optString("detailScreen").equalsIgnoreCase("Y") ? true : false);

                /* for embedded video */
                modle.setEmbeddedThumb(articleJson.optString("embedded_thumbnail"));
                modle.setEmbeddedVideoUrl(articleJson.optString("embedded_video_link"));

            }

        }

        return modle;

    }

    @Override
    public Collection<IModel> parse(JSONArray array) throws JSONException {
        return null;
    }

    @Override
    public ArrayList<IModel> parse(String resp) throws JSONException {
        return null;
    }
}
