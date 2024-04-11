import {EditGuesser, InputGuesser} from "@api-platform/admin";

const MenuEdit = (props:{props:any}) => (
  <EditGuesser {...props}>
    <InputGuesser source={"path"} />
    <InputGuesser source={"isBottomMenu"} />
    <InputGuesser source={"isLeftMenu"} />
    <InputGuesser source={"isTopMenu"} />
    <InputGuesser source={"parent"} />
    <InputGuesser source={"name"} />
    <InputGuesser source={"lft"} />
    <InputGuesser source={"rgt"} />
    <InputGuesser source={"lvl"} />
    <InputGuesser source={"tree"} />
    <InputGuesser source={"parentId"} />
    <InputGuesser source={"createdAt"} />
    <InputGuesser source={"updatedAt"} />
    <InputGuesser source={"slug"} />
    <InputGuesser source={"status"} />
    <InputGuesser source={"type"} />
  </EditGuesser>
);

export default MenuEdit;
