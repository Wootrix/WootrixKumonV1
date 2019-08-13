package com.ta.wootrix.modle;

import android.os.Parcel;
import android.os.Parcelable;

public class ArticleModle implements IModel {

    public static final Parcelable.Creator<ArticleModle> CREATOR = new Parcelable.Creator<ArticleModle>() {
        @Override
        public ArticleModle createFromParcel(Parcel source) {
            return new ArticleModle(source);
        }

        @Override
        public ArticleModle[] newArray(int size) {
            return new ArticleModle[size];
        }
    };
    String articleID, articleType, title, CreateDate, source, articleDescPlain, articleDescHtml, coverPhotoUrl, VideoURL,
            CommentCount/*
                         * , ArticleOwner
						 */, fullSource;
    String embeddedThumb, embeddedVideoUrl, createdSource;
    boolean canComment, canShare, detailLoadFromHtmlData, canSeeDetail;
    int TotalPages;
    String coverImageMag = "";

    public ArticleModle(Parcel source) {
        articleID = source.readString();
        articleType = source.readString();
        title = source.readString();
        CreateDate = source.readString();
        this.source = source.readString();
        fullSource = source.readString();
        articleDescPlain = source.readString();
        articleDescHtml = source.readString();
        coverPhotoUrl = source.readString();
        VideoURL = source.readString();
        CommentCount = source.readString();
        embeddedThumb = source.readString();
        embeddedVideoUrl = source.readString();
        createdSource = source.readString();
        // ArticleOwner = source.readString();
        canComment = source.readByte() != 0;
        canShare = source.readByte() != 0;
        detailLoadFromHtmlData = source.readByte() != 0;

    }

    public ArticleModle() {
    }

    public String getCreatedSource() {
        return createdSource;
    }

    public void setCreatedSource(String createdSource) {
        this.createdSource = createdSource;
    }

    public String getEmbeddedThumb() {
        return embeddedThumb;
    }

    public void setEmbeddedThumb(String embeddedThumb) {
        this.embeddedThumb = embeddedThumb;
    }

    public String getEmbeddedVideoUrl() {
        return embeddedVideoUrl;
    }

    public void setEmbeddedVideoUrl(String embeddedVideoUrl) {
        this.embeddedVideoUrl = embeddedVideoUrl;
    }

    public String getFullSource() {
        return fullSource;
    }

    public void setFullSource(String fullSource) {
        this.fullSource = fullSource;
    }

    public String getCoverImageMag() {
        return coverImageMag;
    }

    public void setCoverImageMag(String coverImageMag) {
        this.coverImageMag = coverImageMag;
    }

    public int getTotalPages() {
        return TotalPages;
    }

    public void setTotalPages(int totalPages) {
        TotalPages = totalPages;
    }

    public boolean isCanSeeDetail() {
        return canSeeDetail;
    }

    public void setCanSeeDetail(boolean canSeeDetail) {
        this.canSeeDetail = canSeeDetail;
    }

    public boolean isDetailLoadFromHtmlData() {
        return detailLoadFromHtmlData;
    }

    public void setDetailLoadFromHtmlData(boolean detailLoadFromHtmlData) {
        this.detailLoadFromHtmlData = detailLoadFromHtmlData;
    }

    public String getArticleID() {
        return articleID;
    }

    public void setArticleID(String articleID) {
        this.articleID = articleID;
    }

    public String getArticleType() {
        return articleType;
    }

    public void setArticleType(String articleType) {
        this.articleType = articleType;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getCreateDate() {
        return CreateDate;
    }

    public void setCreateDate(String createDate) {
        CreateDate = createDate;
    }

    public String getSource() {
        return source;
    }

    public void setSource(String source) {
        this.source = source;
    }

    public String getArticleDescPlain() {
        return articleDescPlain;
    }

    public void setArticleDescPlain(String articleDescPlain) {
        this.articleDescPlain = articleDescPlain;
    }

    public String getArticleDescHtml() {
        return articleDescHtml;
    }

    public void setArticleDescHtml(String articleDescHtml) {
        this.articleDescHtml = articleDescHtml;
    }

    public String getCoverPhotoUrl() {
        return coverPhotoUrl;
    }

    public void setCoverPhotoUrl(String coverPhotoUrl) {
        this.coverPhotoUrl = coverPhotoUrl;
    }

    public String getVideoURL() {
        return VideoURL;
    }

    public void setVideoURL(String videoURL) {
        VideoURL = videoURL;
    }

    public String getCommentCount() {
        return CommentCount;
    }

    // public String getArticleOwner()
    // {
    // return ArticleOwner;
    // }
    //
    // public void setArticleOwner(String articleOwner)
    // {
    // ArticleOwner = articleOwner;
    // }

    public void setCommentCount(String commentCount) {
        CommentCount = commentCount;
    }

    public boolean isCanComment() {
        return canComment;
    }

    public void setCanComment(boolean canComment) {
        this.canComment = canComment;
    }

    public boolean isCanShare() {
        return canShare;
    }

    public void setCanShare(boolean canShare) {
        this.canShare = canShare;
    }

    @Override
    public int describeContents() {
        return 0;
    }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        dest.writeString(articleID);
        dest.writeString(articleType);
        dest.writeString(title);
        dest.writeString(CreateDate);
        dest.writeString(this.source);
        dest.writeString(fullSource);
        dest.writeString(articleDescPlain);
        dest.writeString(articleDescHtml);
        dest.writeString(coverPhotoUrl);
        dest.writeString(VideoURL);
        dest.writeString(CommentCount);
        dest.writeString(embeddedThumb);
        dest.writeString(embeddedVideoUrl);
        dest.writeString(createdSource);
        // dest.writeString(ArticleOwner);
        dest.writeByte((byte) (canComment ? 1 : 0));
        dest.writeByte((byte) (canShare ? 1 : 0));
        dest.writeByte((byte) (detailLoadFromHtmlData ? 1 : 0));
    }
}
