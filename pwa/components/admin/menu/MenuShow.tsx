import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

const MenuShow = (props:{props:any})  => (
  <ShowGuesser {...props}>
    <FieldGuesser source={"path"} />
    <FieldGuesser source={"isBottomMenu"} />
    <FieldGuesser source={"isLeftMenu"} />
    <FieldGuesser source={"isTopMenu"} />
    <FieldGuesser source={"parent"} />
    <FieldGuesser source={"name"} />
    <FieldGuesser source={"lft"} />
    <FieldGuesser source={"rgt"} />
    <FieldGuesser source={"lvl"} />
    <FieldGuesser source={"tree"} />
    <FieldGuesser source={"parentId"} />
    <FieldGuesser source={"createdAt"} />
    <FieldGuesser source={"updatedAt"} />
    <FieldGuesser source={"defaultStatus"} />
    <FieldGuesser source={"defaultType"} />
    <FieldGuesser source={"slug"} />
    <FieldGuesser source={"status"} />
    <FieldGuesser source={"type"} />
  </ShowGuesser>
);

export default MenuShow;
